<h1 class="title">Contacter nous dès maintenant.</h1>

<form action="/contact" method="POST" class="contact-form">
    <div class="group">
        <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Votre Nom</label>
            <input type="text" id="name" name="name" required>
            <div class="error-message" data-error="name"></div>
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Votre Email</label>
            <input type="email" id="email" name="email" required>
            <div class="error-message" data-error="email"></div>
        </div>
    </div>
    <div class="form-group">
        <label for="tel"><i class="fas fa-phone"></i> Votre numéro de téléphone</label>
        <input type="tel" id="tel" name="tel" required>
        <div class="error-message" data-error="tel"></div>
    </div>
    <div class="form-group">
        <label for="message"><i class="fas fa-comment"></i> Votre Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>
        <div class="error-message" data-error="message"></div>
    </div>
    <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
</form>
