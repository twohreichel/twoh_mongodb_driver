(async function() {
    const canvasBrowseCollection = document.getElementById('browse.collection.view.bar.chart');

    if (canvasBrowseCollection) {
        const dataBrowseCollection = [
            { year: 2010, count: 10 },
            { year: 2011, count: 20 },
            { year: 2012, count: 15 },
            { year: 2013, count: 25 },
            { year: 2014, count: 22 },
            { year: 2015, count: 30 },
            { year: 2016, count: 28 },
        ];

        new Chart(
            canvasBrowseCollection,
            {
                type: 'bar',
                data: {
                    labels: dataBrowseCollection.map(row => row.year),
                    datasets: [
                        {
                            label: 'Acquisitions by year',
                            data: dataBrowseCollection.map(row => row.count)
                        }
                    ]
                }
            }
        );
    }
})();