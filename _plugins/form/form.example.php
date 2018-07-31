<!-- Add the "data-form" attribute if you're using Javascript validation. Recommended! -->
<form id="quick" action="/contact" method="post" data-form>

    <!-- Include the form ID as set in your config -->
    <input type="hidden" name="bp-form-id" value="quick">

    <!--

    The form handler will set alerts depending on how the submission goes
    Include this on the page you're submitting to (the action attribute)

    Note: Javascript validation may place errors in this spot regardless
    of the final destination.

    -->
    <?php if ( $alerts = get('form.alerts') ): ?>
        <div class="form-alerts">
            <?php foreach ( $alerts as $alert ): ?>
            <div class="form-alert <?php echo $alert['type']; ?>"><?php echo $alert['message']; ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!--

    Just like the last plugin, input names must match the configured
    fields in your .config.yml

    See .config.example.yml for an example config. It's mostly the same
    as before, but with new features :)

    The Javascript validation will add a P tag with the class "form-error"
    underneath each input which fails validation.

    -->
    <div class="field">
        <input name="name" type="text" placeholder="Name">
    </div>
    <div class="field">
        <input name="email" type="email" placeholder="E-mail address">
    </div>
    <div class="field">
        <input name="phone" type="tel" placeholder="Phone number">
    </div>
    <div class="field">
        <textarea name="message" placeholder="Enter your message"></textarea>
    </div>

    <!--

    IMPORTANT: Do not give your submit button an ID "submit" - it conflicts
    with the Javascript handling of submissions and breaks things.

    -->
    <div class="field">
        <button type="submit" class="submit">Submit</button>
    </div>
</form>
