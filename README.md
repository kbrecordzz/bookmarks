# bookmarks

# IMPORTANT - This has security flaws. See it as a code example to learn from and not an app to set up and use with the belief that it will work securely.

Super simple bookmarking web "app" (= web site) for you who have your own web server with PHP on it.

HOW TO USE IT:

1. Have your own web server with PHP installed on it. If you have this I assume you know how to set up a website too. So, put index.php where you want it.
2. Create the directory "data" in the same directory as index.php, and make it able to write to (in Linux: chmod 777 data/ - very unsecure because it gives EVERYONE permission to do ANYTHING, but it works).
3. Set your own password in index.php ($password = "password").

PROBLEMS: the data is visible on the web for everyone who has the link to the data/data.txt file... have to rethink this and upload a more secure version some time... /230718

LICENSE: kbrecordzz public domain license = use it however you want without needing to credit me (basically CC0 but much shorter legal text).

/kbrecordzz
