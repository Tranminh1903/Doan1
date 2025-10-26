<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Doanh thu theo phim</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { text-align: center; margin-bottom: 20px; }
        #chart-container {
            max-width: 700px;
            height: 400px;
            margin: 0 auto;
        }
        .ranges { list-style: none; padding: 0; display: flex; justify-content: center; gap: 10px; margin-bottom: 15px; }
        .ranges li a { display: inline-block; padding: 5px 12px; background: #ddd; text-decoration: none; border-radius: 4px; }
        .ranges li.active a { background: #4caf50; color: white; }
    </style>
</head>
<body>
    <h2>Doanh thu theo từng phim</h2>

    <div id="chart-container">
        <canvas id="movieRevenueChart"></canvas>
    </div>

    <script>
        $(function(){
            const ctx = document.getElementById('movieRevenueChart').getContext('2d');
            let chart = new Chart(ctx, {
                type: 'bar',
                data: { labels: [], datasets: [{ label: 'Doanh thu (VNĐ)', data: [], backgroundColor: 'rgba(255, 159, 64, 0.6)' }] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: 'Tên phim' } },
                        y: { beginAtZero: true, title: { display: true, text: 'Doanh thu (VNĐ)' } }
                    },
                    plugins: { legend: { display: true } }
                }
            });

            function loadData(){
                $.ajax({
                    url: "{{ route('reports.revenue.movie.ajax') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(res){
                        const labels = res.map(r => r.title);
                        const data = res.map(r => parseFloat(r.total));
                        chart.data.labels = labels;
                        chart.data.datasets[0].data = data;
                        chart.update();
                    },
                    error: function(){
                        alert("Không tải được dữ liệu!");
                    }
                });
            }

            loadData(); 
        });
    </script>
</body>
</html>
