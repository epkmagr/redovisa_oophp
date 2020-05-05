<div class="status">
    <h1>Användarstatus</h1>

    <p>Du är inloggad som användare <strong><%= user %></strong>.</p>

    <p>Sessionen innehåller:</p>

    <pre><%= JSON.stringify(session, null, 4) %></pre>

</div>
