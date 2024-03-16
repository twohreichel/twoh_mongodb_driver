(async function() {
    const canvasIndex = document.getElementById('index.view.bar.chart');

    if (canvasIndex) {
        const dataIndex = [
            { year: 2010, count: 10 },
            { year: 2011, count: 20 },
            { year: 2012, count: 15 },
            { year: 2013, count: 25 },
            { year: 2014, count: 22 },
            { year: 2015, count: 30 },
            { year: 2016, count: 28 },
        ];

        new Chart(
            canvasIndex,
            {
                type: 'bar',
                data: {
                    labels: dataIndex.map(row => row.year),
                    datasets: [
                        {
                            label: 'Acquisitions by year',
                            data: dataIndex.map(row => row.count)
                        }
                    ]
                }
            }
        );
    }
})();