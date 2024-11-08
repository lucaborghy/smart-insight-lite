(function($) {
    $(document).ready(function() {
        // Effettua la richiesta AJAX per ottenere i dati
        const data = {
            action: 'get_visits_chart_data',
            nonce: visitsCharAnalytics.nonce
        };
        
        $.post(visitsCharAnalytics.ajax_url, data, function(response) {
            if (response.success) {
                const ctx = document.getElementById('visitsChart').getContext('2d');
                const visitsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: response.data.dates,
                        datasets: [{
                            label: 'Numero di Visite',
                            data: response.data.visit_counts,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                type: 'category',
                                time: {
                                    unit: 'day'
                                },
                                title: {
                                    display: true,
                                    text: 'Data'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Numero di Visite'
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Errore caricamento dati:', response.error)
            }
        });
    });

})(jQuery);