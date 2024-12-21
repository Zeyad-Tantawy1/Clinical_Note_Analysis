

document.addEventListener("DOMContentLoaded", () => {
    
    const ctx2 = document.getElementById("statusChart").getContext("2d");
  
    // Static data for testing purposes
    const staticData = [10, 20, 30, 40, 50];  // Example data
  
    // Notes Analyzed Per Day Chart (Line Chart)
   
  
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