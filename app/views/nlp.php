<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Analysis</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/nlp.css">
</head>
<body>
    <?php include('partials/header.php'); ?>

    <div class="analyze-container">
        <h1>AI Clinical Note Analysis</h1>
        <p class="subtitle">Upload your clinical notes or enter text for AI analysis</p>

        <div class="input-methods">
            <!-- Text Input -->
            <div class="input-section">
                <h2>Enter Text</h2>
                <textarea 
                    id="textInput" 
                    placeholder="Enter your clinical notes here..."
                ></textarea>
            </div>

            <!-- File Upload -->
            <div class="input-section">
                <h2>Upload File</h2>
                <div class="upload-area" id="uploadArea">
                    <input type="file" id="fileInput" accept=".txt,.doc,.docx,.pdf,image/*" hidden>
                    <div class="upload-content">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        <p>Drag & Drop files here or</p>
                        <button class="browse-btn">Browse Files</button>
                        <p class="file-types">Supported formats: TXT, DOC, DOCX, PDF, Images</p>
                    </div>
                </div>
                <div id="fileList" class="file-list"></div>
            </div>
        </div>

        <div class="analyze-actions">
            <button id="analyzeBtn" class="analyze-btn">Analyze</button>
        </div>

        <div id="results" class="results-section" style="display: none;">
            <h2>Analysis Results</h2>
            <div class="results-content">
                <!-- Results will be populated here -->
            </div>
        </div>
    </div>

    <?php include('partials/footer.php'); ?>
    <script src="http://localhost/project/public/assets/js/nlp.js"></script>
</body>
</html>