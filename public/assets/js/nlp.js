document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const results = document.getElementById('results');
    const textInput = document.getElementById('textInput');

    // ... (keep existing file handling code) ...

    // Update the analyze button click handler
    analyzeBtn.addEventListener('click', async () => {
        const text = textInput.value.trim();
        
        if (!text && fileList.children.length === 0) {
            alert('Please enter text or upload a file to analyze');
            return;
        }

        // Show loading state
        analyzeBtn.disabled = true;
        analyzeBtn.textContent = 'Analyzing...';
        results.style.display = 'none';

        try {
            // If there's a file, handle file reading
            if (fileList.children.length > 0) {
                const file = fileInput.files[0];
                if (file.type.startsWith('image/')) {
                    // Handle image files differently if needed
                    alert('Image analysis is not yet implemented');
                    return;
                }
                const fileText = await readFileContent(file);
                await analyzeText(fileText);
            } else {
                // Analyze entered text
                await analyzeText(text);
            }
        } catch (error) {
            console.error('Analysis error:', error);
            alert('An error occurred during analysis. Please try again.');
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtn.textContent = 'Analyze';
        }
    });

    // Function to read file content
    async function readFileContent(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = (e) => resolve(e.target.result);
            reader.onerror = (e) => reject(e);
            reader.readAsText(file);
        });
    }

    // Function to send text to backend for analysis
    async function analyzeText(text) {
        try {
            console.log('Sending text for analysis:', text); // Debug log
    
            const response = await fetch('index.php?url=nlp/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ text: text })
            });
    
            console.log('Response status:', response.status); // Debug log
    
            const data = await response.json();
            console.log('Response data:', data); // Debug log
    
            if (data.error) {
                throw new Error(data.error);
            }
    
            // Display results
            results.style.display = 'block';
            const resultsContent = results.querySelector('.results-content');
            resultsContent.innerHTML = `
                <div class="analysis-result">
                    ${data.result.replace(/\n/g, '<br>')}
                </div>
            `;
        } catch (error) {
            console.error('Detailed error:', error); // More detailed error logging
            alert('Error details: ' + error.message); // Show specific error message
            throw error;
        }
    }
});