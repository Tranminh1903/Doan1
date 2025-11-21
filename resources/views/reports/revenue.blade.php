<!-- resources/views/reports/revenue.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Doanh thu</title>
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
    <h2>Doanh thu</h2>

    <!-- Tabs chọn khoảng thời gian -->
    <ul class="ranges">
        <li class="active"><a href="#" data-range="7">7 Ngày</a></li>
        <li><a href="#" data-range="30">30 Ngày</a></li>
        <li><a href="#" data-range="365">1 Năm</a></li>
    </ul>

    <div id="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>

    <script>
        $(function(){
            const ctx = document.getElementById('revenueChart').getContext('2d');

            let chart = new Chart(ctx, {
                type: 'bar', 
                data: { labels: [], datasets: [{ label: 'Doanh thu (VNĐ)', data: [], backgroundColor: 'rgba(75, 192, 192, 0.6)' }] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: 'Ngày' } },
                        y: { beginAtZero: true, title: { display: true, text: 'Doanh thu (VNĐ)' } }
                    },
                    plugins: { legend: { display: true } }
                }
            });

            function loadData(days){
                $.ajax({
                    url: "{{ route('reports.revenue.ajax') }}",
                    type: "GET",
                    data: { days: days },
                    dataType: "json",
                    success: function(res){
                        const labels = res.map(r => r.date);
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

            
            loadData(7);

            $('ul.ranges a').click(function(e){
                e.preventDefault();
                $('ul.ranges li').removeClass('active');
                $(this).parent().addClass('active');
                const days = $(this).data('range');
                loadData(days);
            });
        });
    </script>
</body>
</html>
