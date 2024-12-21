

document.addEventListener("DOMContentLoaded", () => {
    const ctx1 = document.getElementById("notesChart").getContext("2d");
    const ctx2 = document.getElementById("statusChart").getContext("2d");
  
    // Static data for testing purposes
    const staticData = [10, 20, 30, 40, 50];  // Example data
  
    // Notes Analyzed Per Day Chart (Line Chart)
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu"],  // Days of the week
            datasets: [{
                label: "Notes Analyzed",
                data: staticData,  // Static data
                borderWidth: 1,
                borderColor: "#3498DB",  // Line color
                fill: false  // Don't fill the area under the line
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Notes Analyzed'
                    }
                }
            }
        }
    });
  
    // Status Distribution Chart (Pie Chart)
    new Chart(ctx2, {
        type: "pie",
        data: {
            labels: ["Completed", "Pending", "Flagged"],  // Status labels
            datasets: [{
                data: [60, 30, 10],  // Static example status counts
                backgroundColor: ["#2ECC71", "#E74C3C", "#F1C40F"],  // Color for each slice
            }]
        }
    });
  });





// new Chart(ctx1, {
//   type: "line",
//   data: {
//       labels: ["Mon", "Tue", "Wed", "Thu", "Fri"],
//       datasets: [
//           {
//               label: "Notes Analyzed",
//               data: [12, 19, 8, 15, 22],
//               borderColor: "#3498DB",
//               borderWidth: 2,
//               fill: false,
//           },
//       ],
//   },
// });

// // Status Distribution Chart
// new Chart(ctx2, {
//   type: "pie",
//   data: {
//       labels: ["Completed", "Pending", "Flagged"],
//       datasets: [
//           {
//               data: [60, 30, 10],
//               backgroundColor: ["#2ECC71", "#E74C3C", "#F1C40F"],
//           },
//       ],
//   },
// });
// });