

document.addEventListener('DOMContentLoaded', () => {
  const occupancyCanvas = document.getElementById('occupancyChart');
  if (occupancyCanvas && typeof Chart !== 'undefined') {
    const occupied = parseInt(occupancyCanvas.dataset.occupied || '0', 10);
    const vacant = parseInt(occupancyCanvas.dataset.vacant || '0', 10);
    const maintenance = parseInt(occupancyCanvas.dataset.maintenance || '0', 10);

    new Chart(occupancyCanvas, {
      type: 'doughnut',
      data: {
        labels: ['Occupied', 'Vacant', 'Maintenance'],
        datasets: [{
          data: [occupied, vacant, maintenance],
          backgroundColor: ['#ef4444', '#10b981', '#8b5cf6'],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'DM Sans' } } }
        },
        cutout: '70%'
      }
    });
  }

  const revenueCanvas = document.getElementById('revenueChart');
  if (revenueCanvas && typeof Chart !== 'undefined') {
    const months = JSON.parse(revenueCanvas.dataset.months || '["May", "Jun", "Jul", "Aug", "Sep"]');
    const billed = JSON.parse(revenueCanvas.dataset.billed || '[25000, 32000, 31000, 34500, 42000]');
    const collected = JSON.parse(revenueCanvas.dataset.collected || '[22000, 30000, 29000, 33000, 38500]');

    new Chart(revenueCanvas, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [
          {
            label: 'Total Billed',
            data: billed,
            backgroundColor: '#0d9488',
            borderRadius: 6
          },
          {
            label: 'Collected',
            data: collected,
            backgroundColor: '#10b981',
            borderRadius: 6
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: { font: { family: 'DM Mono' } },
            grid: { color: '#f1f5f9' }
          },
          x: {
            grid: { display: false }
          }
        },
        plugins: {
          legend: { position: 'top', labels: { boxWidth: 12, font: { family: 'DM Sans' } } }
        }
      }
    });
  }

  const billsPieCanvas = document.getElementById('billsStatusChart');
  if (billsPieCanvas && typeof Chart !== 'undefined') {
    const paid = parseInt(billsPieCanvas.dataset.paid || '0', 10);
    const partial = parseInt(billsPieCanvas.dataset.partial || '0', 10);
    const unpaid = parseInt(billsPieCanvas.dataset.unpaid || '0', 10);

    new Chart(billsPieCanvas, {
      type: 'pie',
      data: {
        labels: ['Paid', 'Partially Paid', 'Unpaid'],
        datasets: [{
          data: [paid, partial, unpaid],
          backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 12 } }
        }
      }
    });
  }
});
