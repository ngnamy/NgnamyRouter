<h1 class="title">Contacter nous dès maintenant.</h1>

<form action="/contact" method="POST" class="contact-form">
    <div class="group">
        <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Votre Nom</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Votre Email</label>
            <input type="email" id="email" name="email" required>
        </div>
    </div>
    <div class="form-group">
        <label for="message"><i class="fas fa-comment"></i> Votre Message</label>
        <textarea id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn"><i class="fas fa-paper-plane"></i> Envoyer</button>
</form>