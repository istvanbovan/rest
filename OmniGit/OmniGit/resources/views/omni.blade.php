<h1>OmniGit</h1>
<h3>You are now authenticated!</h3>
<p>First select the repository you'd like to migrate. Second type in your new repository name as the target. Finally press the button to start the migration!</p>

<table>
    <tr>
        <td>Source Repo: (<span id="sourceProvider"></span>)</td>
        <td>
            <select id="sourceList">
            </select>
        </td>
    </tr>
    <tr>
        <td>Target Repo Name: (<span id="targetProvider"></span>)</td>
        <td>
            <input id="targetRepoName" type="text">
        </td>
    </tr>
</table>
<button onclick="migrate()">Migrate</button>
<span id="errorMessage" style="display: none;">Fill in the target repository name!</span>


<script type="application/javascript">
    var repos = {!! json_encode($repos) !!};
    var currentDiv = document.getElementById("sourceList");

    var sourceProvider = "{{ $source }}";
    document.getElementById("sourceProvider").append(sourceProvider);
    var targetProvider = "{{ $target }}";
    document.getElementById("targetProvider").append(targetProvider);

    for (var i = 0; i < repos.length; i++) {
        var option = document.createElement("option");
        option.text = repos[i].name;
        currentDiv.add(option);
    }

    function migrate() {
        var sourceName = sourceProvider + '-' + document.getElementById("sourceList").value;
        var targetName = document.getElementById("targetRepoName").value;

        if (targetName.trim() === '') {
            document.getElementById("errorMessage").style.display = 'inline';
            return;
        }

        targetName = targetProvider + '-' + targetName;

        window.location.href = "/migrate/" + sourceName + '_' + targetName;
    }

</script>

