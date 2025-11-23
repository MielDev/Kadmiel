// Configuration SMTP (à remplacer par vos informations)
const SMTP_CONFIG = {
    Host: 'smtp.gmail.com',
    Username: 'moignon168@gmail.com', // Votre adresse Gmail
    Password: 'lrqg qjgr elrp fjaf', // Votre mot de passe d'application
    To: 'kadmieltognon5@gmail.com', // Votre adresse de réception
    From: 'moignon168@gmail.com', // Doit être la même que Username
    Port: 587,
    Secure: false, // true pour le port 465, false pour les autres ports
    Tls: true
};

// Attendre que le DOM soit complètement chargé
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM entièrement chargé');

    const contactForm = document.querySelector('form.contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const formMessage = document.getElementById('form-message');

    if (!contactForm) {
        console.error('Formulaire non trouvé');
        return;
    }

    console.log('Formulaire trouvé:', contactForm);

    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        console.log('Soumission du formulaire');

        // Désactiver le bouton d'envoi
        submitBtn.disabled = true;
        submitBtn.value = 'Envoi en cours...';
        formMessage.textContent = '';
        formMessage.className = 'mt-3';

        // Récupérer les données du formulaire
        const formData = new FormData(contactForm);
        console.log('Données du formulaire:', Object.fromEntries(formData.entries()));

        // URL du point de terminaison de l'API
        const apiUrl = '/Kadmiel/forms/contact.php';
        // Si vous utilisez XAMPP avec le dossier htdocs comme racine, utilisez plutôt :
        // const apiUrl = '/Kadmiel/forms/contact.php';
        console.log('Envoi de la requête à:', apiUrl);

        // Envoyer les données au serveur
        fetch(apiUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => {
                console.log('Réponse reçue, statut:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Réponse non-OK:', text);
                        throw new Error(`Erreur HTTP: ${response.status} - ${response.statusText}\n${text}`);
                    });
                }
                return response.json().catch(() => {
                    console.error('Impossible de parser la réponse JSON');
                    throw new Error('Réponse du serveur invalide');
                });
            })
            .then(data => {
                console.log('Réponse JSON:', data);
                if (data.status === 'success') {
                    formMessage.textContent = data.message;
                    formMessage.className = 'mt-3 text-success';
                    contactForm.reset();
                } else {
                    throw new Error(data.message || 'Erreur inconnue du serveur');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'envoi du formulaire:', error);
                formMessage.textContent = error.message || 'Une erreur est survenue. Veuillez réessayer plus tard.';
                formMessage.className = 'mt-3 text-danger';
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.value = 'Envoyer le message';
                console.log('Traitement du formulaire terminé');
            });
    });

    console.log('Gestionnaire d\'événements de formulaire attaché');
});
