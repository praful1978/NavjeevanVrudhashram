<section class="card">
  <h1>संपर्क</h1>
  <form class="form" method="post" action="/send_contact.php" onsubmit="return validateContactForm(event)">
    <div class="row">
      <label>नाव <span class="req">*</span>
        <input type="text" name="name" required>
      </label>
      <label>मोबाईल <span class="req">*</span>
        <input type="tel" name="phone" required pattern="[0-9+\-\s]{8,}">
      </label>
    </div>
    <div class="row">
      <label>ई-मेल
        <input type="email" name="email">
      </label>
      <label>विषय
        <input type="text" name="subject">
      </label>
    </div>
    <label>संदेश <span class="req">*</span>
      <textarea name="message" rows="5" required></textarea>
    </label>
    <!-- Honeypot -->
    <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
    <button class="btn" type="submit">पाठवा</button>
    <p class="muted small">* आम्ही तुमची माहिती फक्त संपर्कासाठी वापरू.</p>
  </form>
</section>
