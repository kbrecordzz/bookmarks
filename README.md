# bookmarks

Super simple bookmarking web "app" (= web site) for you who have your own web server with PHP on it.

HOW TO USE IT:

1. Have your own web server with PHP installed on it. If you have this, I assume you know how to set up a website too. So, put index.php where you want it.
2. Create the directory "data" in the same directory as index.php. This is where all the data is stored. Make the directory able to write to (in Linux: chmod 777 data/ - this is unsecure because it gives EVERYONE permission to do ANYTHING, but it works).
3. Set your own password in index.php (if ($_POST["login"] == "password").

BUGS: as of 230705, you need to refresh the site again after having logged in, to actually log in (actually, you are logged in but you don't see the site yet). And minor problem is that the parameters after the URL (?del&data=category;url;imagelink;title&category=gr8) for example) will stay after doing certain actions, and then when you reload the page you will do those actions again... Not really perfect.

LICENSE: kbrecordzz public domain license = use it however you want without needing to credit me (this is basically CC0 but as you see my license's legal text is much shorter).

/kbrecordzz
