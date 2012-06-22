1. Datei <code>lib/disqus.api.keys.php</code> erstellen mit folgendem Inhalt:
<pre><code><?php
  const ACCESS_TOKEN = '';
  const API_KEY = ''; // Consumer Key
  const API_SECRET = ''; // Consumer Secret
</code></pre>
2. Unter http://disqus.com/api/applications/ eine neue API Apllication erstellen und die generierten Keys in der <code>lib/disqus.api.keys.php</code> eintragen.
3. In der <code>result.php</code> in Zeile 4 das Forum (shortname) und die Thread id eintragen. Die Thread id bekommt man unter http://disqus.com/api/console/ , indem man ein <code>FORUM listThreads</code> Request mit dem Parameter <code>forum=shortname</code> ausführt.