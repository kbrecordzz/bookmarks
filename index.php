<style>
* { box-sizing: border-box; }
body { margin: 0 auto !important; float: none !important; }
input { width: 40%; }
div { max-width: 240px; padding-left: 10px; padding-right: 10px; padding-top: 5px; padding-bottom: 5px; margin: 10px; background-color: #E2E2E2; }
.column { float: left; width: 100%; padding: 10px; }
</style>

<?php

/* kolla om man är inloggad */
if (!isset($_COOKIE["bookmarks_loggedin"]))
{
        echo "<form method=\"POST\" action=\"index.php?login\"><input type=\"password\" name=\"login\"> <input type=\"submit\" text=\"button\"></form>";
	if (isset($_GET["login"])) { if ($_POST["login"] == "password") { setcookie("bookmarks_loggedin", "bookmarks_loggedin", time()+60*60*24*30); } }
}
else
{
if (isset($_GET["logout"])) setcookie("bookmarks_loggedin", '', time()-3000);
if (isset($_GET["add"])) add();
if (isset($_GET["del"])) file_put_contents("data/data.txt", str_replace($_GET["data"], "!" . $_GET["data"], file_get_contents("data/data.txt")));
if (isset($_GET["change"]))
{
	$b = explode(";", $_GET["old"]);
	$b[0] = $_GET["new"];
	$new = $b[0] . ";" . $b[1] . ";" . $b[2] . ";" . $b[3];
	file_put_contents("data/data.txt", str_replace($_GET["old"], $new, file_get_contents("data/data.txt")));
}

echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";

$phrase = "";
if (isset($_GET["s"])) $phrase = $_GET["s"];

$a = explode("\n", file_get_contents("data/data.txt"));
$count = count($a)-2;
$limit = 30;
if (isset($_GET["all"])) $limit = 999999;

/* layout */
echo "<head><title>bookmarks</title></head>";

/* panel */
echo "<div class=column><p>";
echo "<center><h1><a href=\"index.php\"></a> ";
echo "<a href=\"index.php\">bookmarks</a>";
echo "</h1></center>";
echo "<p><i>";
if (isset($_GET["category"])) echo "<a href=\"?category=" . $_GET["category"] . "&s=phrase\">&s=phrase</a><br>"; else echo "<a href=\"?s=phrase\">?s=phrase</a><br>";
if (isset($_GET["category"])) echo "<a href=\"?category=" . $_GET["category"] . "&all\">&all</a><br>"; else echo "<a href=\"?all\">?all</a><br>";
echo "<a href=\"data/data.txt\">history</a></i></p>";
echo "<form action=\"index.php\" method=\"get\">";
echo "<input type=\"hidden\" name=\"add\">";
echo "<input type=\"textbox\" name=\"url\"> ";
if (isset($_GET["category"])) echo "<input type=\"hidden\" name=\"category\" value=\"" . $_GET["category"] . "\">";
echo "<input type=\"submit\" value=\"Add\">";
echo "</form>";
$categorylist[0] = "";
$l = 1;
for ($k = 0; $k < $count+1; $k++)
{
	$c = explode(";", $a[$k]);

	$exists = 0;
	for ($m = 0; $m < count($categorylist); $m++)
	{
		if ($categorylist[$m] === $c[0]) { $exists = 1; $number[$m]++; }
	}
	if ($exists == 0 && strpos($c[0], "!") !== 0)
	{
		$categorylist[$l] = $c[0];
		$number[$m] = 0;
		$l++;
	}
}
for ($l = 0; $l < count($categorylist); $l++)
{
	echo "<font size=" . (sqrt(sqrt($number[$l]))+1) . ">";
	echo "<a href=\"index.php?category=" . $categorylist[$l];
	echo "\">(" . $categorylist[$l] . ")</a> ";
	echo "</font>";
}

echo "</div>";

$i = $count;
$ii = 0;
while ($ii < $limit && $i >= 0)
{
	if ($phrase == "" || strpos(strtolower($a[$i]), strtolower($phrase)) !== false)
	{
		if ((!isset($_GET["category"]) || isset($_GET["category"]) && strpos($a[$i], $_GET["category"] . ";") === 0) && strpos($a[$i], "!") !== 0)
		{
			$b = explode(";", $a[$i]);

			echo "<div class=column>";

			if ($b[0] == "") echo "<form action=\"index.php\" method=\"GET\"><input type=\"hidden\" name=\"change\"><input type=\"hidden\" name=\"old\" value=\"" . $a[$i] . "\"><input type=\"textbox\" name=\"new\"><input type=\"submit\" value=\"new category\"></form>";

			echo "<a target=_blank href=\"" . $b[1]  . "\">";
			if (strlen($b[1]) > 0 && substr($b[1], 0, 4) == "http")
			{
				echo "<img width=100% src=\"data/" . preg_replace("/[^A-Za-z0-9 ]/","", str_replace(";","", $b[1])) . ".jpg\"></img><br>";
			}
			echo $b[3] . "</a>";
			if (isset($_GET["category"])) echo $row["title"] . "</a> <a href=\"index.php?del&data=" . $b[0] . ";" . $b[1] . ";" . $b[2] . ";" . $b[3] . "&category=" . $_GET["category"] . "\">&#128465;</a>";
			else echo $row["title"] . "</a> <a href=\"index.php?del&data=" . $b[0] . ";" . $b[1] . ";" . $b[2] . ";" . $b[3] . "\">&#128465;</a>";

			echo "</div></form>";

			$ii++;
		}	
	}
	$i--;
}
}

function add()
{
$html = file_get_contents($_GET["url"]);

$title_prefix = "";

/* hitta bild och titel */
if (strpos($_GET["url"], "youtube.com/watch") !== false)
{
	$img = "http://img.youtube.com/vi/" . substr($_GET["url"], strpos($_GET["url"], "v=")+2, 11) . "/hqdefault.jpg";

	$html_2 = explode("<link itemprop=\"name\" content=\"", $html);
	$html_3 = explode("\"", $html_2[1]);
	$title_prefix = $html_3[0] . " - ";
	$title_prefix = str_replace(" - Topic", "", $title_prefix);
}
else if(strpos($_GET["url"], "youtu.be") !== false)
{
	$img = "http://img.youtube.com/vi/" . substr($_GET["url"], strpos($_GET["url"], ".be/")+4, 11) . "/hqdefault.jpg";

	// kopierat från ovanför
	$html_2 = explode("<link itemprop=\"name\" content=\"", $html);
        $html_3 = explode("\"", $html_2[1]);
        $title_prefix = $html_3[0] . " - ";
        $title_prefix = str_replace(" - Topic", "", $title_prefix);
}
else if (strpos($_GET["url"], "youtube.com/playlist") !== false)
{
	$html_2 = explode("\"data\":[{\"url\":\"", $html);
	$html_3 = explode("\"", $html_2[1]);
	$img = $html_3[0];

	$html_2 = explode("\"shortBylineText\":{\"runs\":[{\"text\":\"", $html);
	$html_3 = explode("\"", $html_2[1]);
	$title_prefix = $html_3[0] . " - ";
	$title_prefix = str_replace(" - Topic", "", $title_prefix);
}
else if (strpos($_GET["url"], "jpg") !== false || strpos($_GET["url"], "png") !== false)
{
	$img = $_GET["url"];
}
else
{
	preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $html, $image);
//	$img = $image['src'];
}

if (isset($_GET["category"])) $category = $_GET["category"];
else $category = "";

$title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $html, $matches) ? $matches[1] : null;
$title = str_replace(" - YouTube", "", $title);
if (strpos($title, " - ") !== false) $title_prefix = "";
file_put_contents("data/data.txt", file_get_contents("data/data.txt") . str_replace(";","",$category) . ";" . str_replace(";","",$_GET["url"]) . ";" . str_replace(";","", $img) . ";" . str_replace(";","", $title_prefix . $title) . "\n");

// spara bild till thumbnails
file_put_contents("data/" . preg_replace("/[^A-Za-z0-9 ]/","", str_replace(";","", $_GET["url"])) . ".jpg", file_get_contents(str_replace("hqdefault", "default", $img)));
//exec("wget \"" . str_replace("hqdefault", "default", $img) . "\" -q -O \"data/" . preg_replace("/[^A-Za-z0-9 ]/","", str_replace(";","", $_GET["url"])) . ".jpg\"");
}

?>
