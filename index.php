<?php

/*
 * W2
 *
 * Copyright (C) 2007-2011 Steven Frank <http://stevenf.com/>
 *
 * Code may be re-used as long as the above copyright notice is retained.
 * See README.txt for full details.
 *
 * Written with Coda: <http://panic.com/coda/>
 *
 */
 
// Install PSR-4-compatible class autoloader
spl_autoload_register(function($class){
	require str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});


// Get Markdown class
use Michelf\MarkdownExtra;


// User configurable options:

include_once "config.php";

ini_set('session.gc_maxlifetime', W2_SESSION_LIFETIME);

session_set_cookie_params(W2_SESSION_LIFETIME);
session_name(W2_SESSION_NAME);
session_start();

if ( count($allowedIPs) > 0 )
{
	$ip = $_SERVER['REMOTE_ADDR'];
	$accepted = false;
	
	foreach ( $allowedIPs as $allowed )
	{
		if ( strncmp($allowed, $ip, strlen($allowed)) == 0 )
		{
			$accepted = true;
			break;
		}
	}
	
	if ( !$accepted )
	{
		print "<html><body>Access from IP address $ip is not allowed";
		print "</body></html>";
		exit;
	}
}

if ( REQUIRE_PASSWORD && !isset($_SESSION['password']) )
{
	if ( !defined('W2_PASSWORD_HASH') || W2_PASSWORD_HASH == '' )
		define('W2_PASSWORD_HASH', sha1(W2_PASSWORD));
	
	if ( (isset($_POST['p'])) && (sha1($_POST['p']) == W2_PASSWORD_HASH) )
		$_SESSION['password'] = W2_PASSWORD_HASH;
	else
	{
		print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		print "<html>\n";
		print "<head>\n";
		print "<link rel=\"apple-touch-icon\" href=\"apple-touch-icon.png\"/>";
		print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=false\" />\n";
		
		print "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . BASE_URI . "/" . CSS_FILE ."\" />\n";
		print "<title>Log In</title>\n";
		print "</head>\n";
		print "<body><form method=\"post\">";
		print "<input type=\"password\" name=\"p\">\n";
		print "<input type=\"submit\" value=\"Go\"></form>";
		print "</body></html>";
		exit;
	}
}

// Support functions

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// makes an <a> tag in the form of  "/rootdir/index.php/[match]", where match is the name of a page (in theory)
//		if the page doesn't exist, it sends you to a weird empty page. So that's an issue.
function _handle_links($match)
{
	return "<a href=\"" . SELF . VIEW . "/" . htmlentities($match[1]) . "\">" . htmlentities($match[1]) . "</a>";
}


function _handle_images($match)
{
	return "<img src=\"" . BASE_URI . "/images/" . htmlentities($match[1]) . "\" alt=\"" . htmlentities($match[1]) . "\" />";
}


function _handle_message($match)
{
	return "[<a href=\"message:" . htmlentities($match[1]) . "\">email</a>]";
}


function printToolbar()
{
	global $upage, $page, $action;

	print "<div class=\"toolbar\">";
	# left hand side
	print "<div>";	
	print "<a class=\"tool\" href=\"/w2/index.php\">Home</a>";
 	print "<a class=\"tool\" href=\"" . SELF . "?action=all_name\">See All Pages</a> ";
	#print "<a class=\"tool\" href=\"" . SELF . "?action=graph\">View Graph</a> ";
	print "</div>";

	# Center
	print "<div id=\"center\">";
	print "<form class=\"tool\" method=\"post\" action=\"" . SELF . "?action=search\">\n";
	print "<input autocomplete=\"on\" placeholder=\"Search all pages and tags...\" size=\"40\" id=\"search\" type=\"text\" name=\"q\" /></form>\n";
	print "</div>";

	# right hand side
	print "<div>";
	print "<a class=\"tool\" href=\"" . SELF . "?action=new\">New Entry</a> ";
	if ( !DISABLE_UPLOADS )
		print "<a class=\"tool\" href=\"" . SELF . VIEW . "?action=upload\">Upload Image</a> ";
	print "</div>";
	if ( REQUIRE_PASSWORD )
		print '<a class="tool" href="' . SELF . '?action=logout">Exit</a>';

	print "</div>\n";
}


function descLengthSort($val_1, $val_2) 
{ 
	$retVal = 0;

	$firstVal = strlen($val_1); 
	$secondVal = strlen($val_2);

	if ( $firstVal > $secondVal ) 
		$retVal = -1; 
	
	else if ( $firstVal < $secondVal ) 
		$retVal = 1; 

	return $retVal; 
}


function toHTML($inText)
{
	global $page;
	
	// removes any html <script> tags
	$inText = preg_replace("/<[\/]*script>/", "", $inText);                

	// not sure what this bit does. Something about looping through the Pages directory?
	$dir = opendir(PAGES_PATH);
	while ( $filename = readdir($dir) )
	{
		if ( $filename[0] == '.' )
			continue;
			
		$filename = preg_replace("/(.*?)\.md/", "\\1", $filename);
		$filenames[] = $filename;
	}
	closedir($dir);
	
	// sorting for some reason?
	uasort($filenames, "descLengthSort"); 

	//
	if ( AUTOLINK_PAGE_TITLES )
	{	
		foreach ( $filenames as $filename )
		{
			// this does a negative lookbehind and also a negative lookahead. Seems to be checking for directory/excess path stuff?
	 		$inText = preg_replace("/(?<![\>\[\/])($filename)(?!\]\>)/im", "<a href=\"" . SELF . VIEW . "/$filename\">\\1</a>", $inText);
		}
	}
	
	// this bit looks for page links somehow
	// trims the double brackets [[X]] that surround a name, then makes it an html <a> tag
	$inText = preg_replace_callback("/\[\[(.*?)\]\]/", '_handle_links', $inText);
	//this bit looks for image embeds somehow
	$inText = preg_replace_callback("/\{\{(.*?)\}\}/", '_handle_images', $inText);
	// replaces text of "message:[email@address]" to a mailto link
	$inText = preg_replace_callback("/message:(.*?)\s/", '_handle_message', $inText);

	// this uses the defaultTransform method defined in MarkdownInterface.php and implemented in Markdown.php
	// but some of it is probably overridden in MarkdownExtra.php
	$html = MarkdownExtra::defaultTransform($inText);
	$inText = htmlentities($inText);

	return $html;
}

function sanitizeFilename($inFileName)
{
	return str_replace(array('..', '~', '/', '\\', ':'), '-', $inFileName);
}

function destroy_session()
{
	if ( isset($_COOKIE[session_name()]) )
		setcookie(session_name(), '', time() - 42000, '/');

	session_destroy();
	unset($_SESSION["password"]);
	unset($_SESSION);
}

// Support PHP4 by defining file_put_contents if it doesn't already exist

if ( !function_exists('file_put_contents') )
{
    function file_put_contents($n, $d)
    {
		$f = @fopen($n, "w");
		
		if ( !$f )
		{
			return false;
		}
		else
		{
			fwrite($f, $d);
			fclose($f);
			return true;
		}
    }
}
// Support PHP 8.1 by setting two predefined variables to empty strings if
// not already defined. Fixes a bunch of deprecation warnings.

if (!isset($_SERVER["PATH_INFO"]))
  $_SERVER["PATH_INFO"] = '';
if (!isset($_REQUEST['page']))
  $_REQUEST['page'] = '';


// Main code

if ( isset($_REQUEST['action']) )
	$action = $_REQUEST['action'];
else 
	$action = 'view';

// Look for page name following the script name in the URL, like this:
// http://stevenf.com/w2demo/index.php/Markdown%20Syntax
//
// Otherwise, get page name from 'page' request variable.

if ( preg_match('@^/@', @$_SERVER["PATH_INFO"]) ) 
	$page = sanitizeFilename(substr($_SERVER["PATH_INFO"], 1));
else 
	$page = sanitizeFilename(@$_REQUEST['page']);

$upage = urlencode($page);

if ( $page == "" )
	$page = DEFAULT_PAGE;

$filename = PAGES_PATH . "/$page.md";

if ( file_exists($filename) )
{
	$text = file_get_contents($filename);
}
else
{
	if ( $action != "save" && $action != "all_name" && $action != "all_date" && $action != "all_images" && $action != "upload" && $action != "new" && $action != "logout" && $action != "uploaded" && $action != "search" && $action != "view" )
	{
		$action = "edit";
	}
}

// Check all actions - if none apply, render the current document
if ( $action == "edit" || $action == "new" )
{
	$formAction = SELF . (($action == 'edit') ? "/$page" : "");
	$html = "<form id=\"edit\" method=\"post\" action=\"$formAction\">\n";

	if ( $action == "edit" )
		$html .= "<input type=\"hidden\" name=\"page\" value=\"$page\" />\n";
	else
		$html .= "<p>Name: <input id=\"title\" type=\"text\" name=\"page\" /></p>\n";

	if ( $action == "new" )
		$text = NEW_FILE_CONTENT;

	//$html .= "<p><textarea id=\"text\" name=\"newText\" rows=\"" . EDIT_ROWS . "\">$text</textarea></p>\n";
	$html .= "<textarea id=\"text_edit\" name=\"newText\" rows=\"" . EDIT_ROWS . "\">$text</textarea>\n";
	
	# Edit specific Scripts
	$html .= "<script src=\"/w2/javascript/textbox_scale.js\"></script>";
	$html .= "<script src=\"/w2/javascript/trap_tab.js\"></script>";
	$html .= "<script src=\"/w2/javascript/trap_control.js\"></script>";

	$html .= "<p><a href=\"/w2/index.php/Markdown Syntax\">Guide to Markdown</a></p>";
	$html .= "<p><input type=\"hidden\" name=\"action\" value=\"save\" />";
	$html .= "<input id=\"save\" type=\"submit\" value=\"Save\" />\n";
	$html .= "<input id=\"cancel\" type=\"button\" onclick=\"history.go(-1);\" value=\"Cancel\" /></p>\n";
	$html .= "</form>\n";
}
else if ( $action == "logout" )
{
	destroy_session();
	header("Location: " . SELF);
	exit;
}
else if ( $action == "upload" )
{
	if ( DISABLE_UPLOADS )
	{
		$html = "<p>Image uploading has been disabled on this installation.</p>";
	}
	else
	{
		$html = "<form id=\"upload\" method=\"post\" action=\"" . SELF . "\" enctype=\"multipart/form-data\"><p>\n";
		$html .= "<input type=\"hidden\" name=\"action\" value=\"uploaded\" />";
		$html .= "<input id=\"file\" type=\"file\" name=\"userfile\" />\n";
		$html .= "<input id=\"upload\" type=\"submit\" value=\"Upload\" />\n";
		$html .= "<input id=\"cancel\" type=\"button\" onclick=\"history.go(-1);\" value=\"Cancel\" />\n";
		$html .= "</p></form>\n";
	}
}
else if ( $action == "uploaded" )
{
	if ( !DISABLE_UPLOADS )
	{
		$dstName = sanitizeFilename($_FILES['userfile']['name']);
		$fileType = $_FILES['userfile']['type'];
		preg_match('/\.([^.]+)$/', $dstName, $matches);
		$fileExt = isset($matches[1]) ? $matches[1] : null;
		
		if (in_array($fileType, explode(',', VALID_UPLOAD_TYPES)) &&
			in_array($fileExt, explode(',', VALID_UPLOAD_EXTS)))
		{
			$errLevel = error_reporting(0);

			if ( move_uploaded_file($_FILES['userfile']['tmp_name'], 
				BASE_PATH . "/images/$dstName") === true ) 
			{
				$html = "<p class=\"note\">File '$dstName' uploaded</p>\n";
			}
			else
			{
				$html = "<p class=\"note\">Upload error</p>\n";
			}

			error_reporting($errLevel);
		} else {
			$html = "<p class=\"note\">Upload error: invalid file type</p>\n";
		}
	}

	$html .= toHTML($text);
}
else if ( $action == "save" )
{
	$newText = $_REQUEST['newText'];

	$errLevel = error_reporting(0);
	$success = file_put_contents($filename, $newText);
 	error_reporting($errLevel);

	if ( $success )	{
		$html = "<p class=\"note\">Saved</p>\n";
		
		// Auto committing to a git repo is on pause for now. 
		//
		//$return_code = `git log -1 >> fuck.log`;
		//$return_code = shell_exec("./commit_change.sh $page");
		//shell_exec("echo $return_code >> log.md");
	}
	else
		$html = "<p class=\"note\">Error saving changes! Make sure your web server has write access to " . PAGES_PATH . "</p>\n";

	$html .= toHTML($newText);
}
/*
else if ( $action == "rename" )
{
	$html = "<form id=\"rename\" method=\"post\" action=\"" . SELF . "\">";
	$html .= "<p>Title: <input id=\"title\" type=\"text\" name=\"page\" value=\"" . htmlspecialchars($page) . "\" />";
	$html .= "<input id=\"rename\" type=\"submit\" value=\"Rename\">";
	$html .= "<input id=\"cancel\" type=\"button\" onclick=\"history.go(-1);\" value=\"Cancel\" />\n";
	$html .= "<input type=\"hidden\" name=\"action\" value=\"renamed\" />";
	$html .= "<input type=\"hidden\" name=\"prevpage\" value=\"" . htmlspecialchars($page) . "\" />";
	$html .= "</p></form>";
}
else if ( $action == "renamed" )
{
	$pp = $_REQUEST['prevpage'];
	$pg = $_REQUEST['page'];

	$prevpage = sanitizeFilename($pp);
	$prevpage = urlencode($prevpage);
	
	$prevfilename = PAGES_PATH . "/$prevpage.md";

	if ( rename($prevfilename, $filename) )
	{
		// Success.  Change links in all pages to point to new page
		if ( $dh = opendir(PAGES_PATH) )
		{
			while ( ($file = readdir($dh)) !== false )
			{
				$content = file_get_contents($file);
				$pattern = "/\[\[" . $pp . "\]\]/g";
				preg_replace($pattern, "[[$pg]]", $content);
				file_put_contents($file, $content);
			}
		}
	}
	else
	{
		$html = "<p class=\"note\">Error renaming file</p>\n";
	}
}
*/
else if ( $action == "all_name" )
{
	$dir = opendir(PAGES_PATH);
	$filelist = array();

	$color = "#ffffff";

	while ( $file = readdir($dir) )
	{
		if ( $file[0] == "." )
			continue;

		$afile = preg_replace("/(.*?)\.md/", "<a href=\"" . SELF . VIEW . "/\\1\">\\1</a>", $file);
		$efile = preg_replace("/(.*?)\.md/", "<a href=\"?action=edit&amp;page=\\1\">edit</a>", urlencode($file));

		array_push($filelist, "<tr style=\"background-color: $color;\"><td>$afile</td><td width=\"20\"></td><td>$efile</td></tr>");

		if ( $color == "#ffffff" )
			$color = "#f4f4f4";
		else
			$color = "#ffffff";
	}

	closedir($dir);

	natcasesort($filelist);
	
	$html = "<table>";


	for ($i = 0; $i < count($filelist); $i++)
	{
		$html .= $filelist[$i];
	}

	$html .= "</table>\n";
}
else if ( $action == "all_date" )
{
	$html = "<table>\n";
	$dir = opendir(PAGES_PATH);
	$filelist = array();
	while ( $file = readdir($dir) )
	{
		if ( $file[0] == "." )
			continue;
			
		$filelist[preg_replace("/(.*?)\.md/", "<a href=\"" . SELF . VIEW . "/\\1\">\\1</a>", $file)] = filemtime(PAGES_PATH . "/$file");
	}

	closedir($dir);

	$color = "#ffffff";
	arsort($filelist, SORT_NUMERIC);

	foreach ($filelist as $key => $value)
	{
		$html .= "<tr style=\"background-color: $color;\"><td valign=\"top\">$key</td><td width=\"20\"></td><td valign=\"top\"><nobr>" . date(TITLE_DATE_NO_TIME, $value) . "</nobr></td></tr>\n";
		
		if ( $color == "#ffffff" )
			$color = "#f4f4f4";
		else
			$color = "#ffffff";
	}
	$html .= "</table>\n";
}
else if ( $action == "all_images" )
{

	$dir = opendir(IMAGES_PATH);
	$filelist = array();

	$color = "#ffffff";

	while ( $file = readdir($dir) )
	{
		if ( $file[0] == "." )
			continue;

		$afile = preg_replace("/(.*?)\.md/", "<a href=\"" . SELF . VIEW . "/\\1\">\\1</a>", $file);
		$efile = preg_replace("/(.*?)\.md/", "<img src=\"" . IMAGES_PATH . "/" . $file . "\"/>", urlencode($file));

		array_push($filelist, "<tr style=\"background-color: $color;\"><td>$afile</td><td width=\"20\"></td><td>$efile</td></tr>");

		if ( $color == "#ffffff" )
			$color = "#f4f4f4";
		else
			$color = "#ffffff";
	}

	closedir($dir);

	natcasesort($filelist);
	
	$html = "<table>";


	for ($i = 0; $i < count($filelist); $i++)
	{
		$html .= $filelist[$i];
	}

	$html .= "</table>\n";

}
else if ( $action == "search" )
{
	$matches = 0;
	$q = $_REQUEST['q'];
	$html = "<h1>Search: $q</h1>\n<ul>\n";

	if ( trim($q) != "" )
	{
		$dir = opendir(PAGES_PATH);
		
		while ( $file = readdir($dir) )
		{
			if ( $file[0] == "." )
				continue;

			$text = file_get_contents(PAGES_PATH . "/$file");
			
                        if ( preg_match("/{$q}/i", $text) || preg_match("/{$q}/i", $file) )
			{
				++$matches;
				$file = preg_replace("/(.*?)\.md/", "<a href=\"" . SELF . VIEW . "/\\1\">\\1</a>", $file);
				$html .= "<li>$file</li>\n";
			}
		}
		
		closedir($dir);
	}

	$html .= "</ul>\n";
	$html .= "<p>$matches matched</p>\n";
}
else
{
	$html = toHTML($text);
}

$datetime = '';

if ( ($action == "all_name") || ($action == "all_date"))
	$title = "All Pages";
	
else if ( $action == "upload" )
	$title = "Upload Image";

else if ( $action == "new" )
	$title = "New";

else if ( $action == "search" )
	$title = "Search";

else
{
	$title = $page;

	if ( TITLE_DATE )
	{
		$datetime = "<span class=\"titledate\">" . date(TITLE_DATE, @filemtime($filename)) . "</span>";
	}
}

// Disable caching on the client (the iPhone is pretty aggressive about this
// and it can cause problems with the editing function)

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
print "<html>\n";
print "<head>\n";
print "<link rel=\"apple-touch-icon\" href=\"apple-touch-icon.png\"/>";
print "<link rel=\"icon\" href=\"/w2/images/icon.png\">";
print "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=false\" />\n";

print "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . BASE_URI . "/" . CSS_FILE ."\" />\n";
print "<title>$title</title>\n";
print "</head>\n";
print "<body id=\"body\">\n";

print "<header>\n";

printToolbar();

print "<div class=\"titlebar\">" .
	"<div class=\"logo\"><a href=\"/w2/index.php\"><img src=\"/w2/images/icon.png\"></a></div>" .
	"<div class=\"title\">$title</div>" .
	"<div class=\"edit-div\"> " .
		"<div><a class=\"first\" href=\"" . SELF . "?action=edit&amp;page=$upage\">Edit Page</a> </div>" .
		"<div class=\"edittime\">Last Edited:</div>" .
		"<div>$datetime</div>" .
	"</div></div>\n";

print "</header>\n";

print "<div id=\"top\" class=\"float_button\">" . 
    	"<a href=\"#body\">Jump to Top</a>" .
	  "</div>";

print "<main id=\"main\">\n";
print "<div id=\"markdown-content\" class=\"content\">\n";
print "$html\n";
print "</div>\n";
print "</main>\n";

print "<div id=\"bottom\" class=\"float_button\">" . 
    	"<a href=\"#foot\">Jump to Bottom</a>" .
	  "</div>";

print "<div class=\"padding\"></div>";

print "<footer id=\"foot\">This is the footer</footer>";
print "</body>\n";

print "<script src=\"/w2/javascript/proccess_intra_page_links.js\"></script>\n";
print "<script src=\"/w2/javascript/fancy_scroll.js\"></script>\n";
print "<script src=\"/w2/javascript/insert_horizontal_rules.js\"></script>\n";

print "</html>\n";

#debug_to_console($action);

?>

