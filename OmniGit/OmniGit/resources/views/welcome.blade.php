<h1>Welcome to OmniGit</h1>

<p>Select the source and target providers from the list:</p>
<table>
    <tr>
        <td>Source Provider:</td>
        <td>
            <select id="sourceProvider">
                <option name="GitHub">GitHub</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Target Provider:</td>
        <td>
            <select id="targetProvider">
                <option name="GitHub">GitHub</option>
            </select>
        </td>
    </tr>
</table>

<p>To start the authentication, press the button below.</p>
<button onclick="authenticate()">Authenticate</button>

<script type="application/javascript">
    function authenticate() {
        var source = document.getElementById("sourceProvider").value;
        var target = document.getElementById("targetProvider").value;

        window.location.href = "/authenticate/" + source + '_' + target;
    }
</script>
