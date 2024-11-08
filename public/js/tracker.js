(function($) {
    // Variabili per tracciare il tempo di permanenza
    let startTime = new Date().getTime();
    let actions = []; // Array per registrare le azioni

    // Funzione per calcolare il tempo trascorso
    function getTimeSpent() {
        let endTime = new Date().getTime();
        return Math.floor((endTime - startTime) / 1000); // Converte in secondi
    }

    // Funzione per ottenere la data della visita
    function getVisitDate() {
        // Ottiene la data e l'ora attuali
        let visitDate = new Date();
        
        // Formatta la data nel formato YYYY-MM-DD HH:MM:SS
        let year = visitDate.getFullYear();
        let month = String(visitDate.getMonth() + 1).padStart(2, '0');
        let day = String(visitDate.getDate()).padStart(2, '0');
        let hours = String(visitDate.getHours()).padStart(2, '0');
        let minutes = String(visitDate.getMinutes()).padStart(2, '0');
        let seconds = String(visitDate.getSeconds()).padStart(2, '0');
        
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    // Funzione per ottenere il paese del visitatore
    function getLanguage() {
        const userLanguage = navigator.language || navigator.userLanguage;
        return userLanguage;
    }

    // Funzione per registrare le azioni dell'utente (clic del mouse)
    function getActions() {
        $(document).on('click', function(event) {
            // Ottiene gli attributi 'id' e 'class' dell'elemento cliccato
            const elementId = event.target.id || "";
            const elementClass = event.target.className || "";
            // Aggiunge ogni clic al registro delle azioni
            actions.push({
                event: event.type,
                element: event.target.tagName, // Tipo di elemento (es. DIV, BUTTON)
                x: event.pageX, // Coordinata X del clic
                y: event.pageY, // Coordinata Y del clic
                id: elementId, // Attributo 'id' dell'elemento, se presente
                class: elementClass, // Attributo 'class' dell'elemento, se presente
                timestamp: new Date().getTime() // Tempo in cui è avvenuto il clic
            });
        });
    }

    // Funzione per inviare i dati via AJAX
    function sendAnalyticsData() {
        // Dati da inviare
        const data = {
            action: 'simple_analytics_track_visit',
            nonce: simpleAnalytics.nonce,
            page_url: window.location.href,
            language: getLanguage(),
            time_spent: getTimeSpent(),
            visit_date: getVisitDate(),
            actions: JSON.stringify(actions) // Trasforma le azioni in una stringa JSON
        };

        // Richiesta AJAX
        $.post(simpleAnalytics.ajax_url, data, function(response) {
            if (response.success) {
                console.log("Dati di analytics inviati con successo.");
            } else {
                console.log("Errore nell'invio dei dati di analytics.");
            }
        });
    }

    // Invia i dati quando l'utente lascia la pagina
    getActions();
    window.addEventListener('beforeunload', sendAnalyticsData);
})(jQuery);
