Welcome to W2
-------------

This is your home page.  Feel free to edit it, and replace the content with anything you like.

For full documentation, please read README.txt, included with the distribution.

Test modification.

Click here to learn more about the [[Markdown Syntax]]. 

Here is the current plan for work on this project: [[Feature List]].

# Key Principles of this Project (OpenMDWiki)
## 1. Markdown first, everything else second
Where possible, all documents should use basic markdown syntax. If you can do it with markdown, do it with markdown. Only use other tools when markdown fails. Why? See principle 2.

## 2. Everything is exportable
No information should be locked within this system. When someone downloads the contents of a page, they should get _all_ the information with it - no hidden metadata, nothing they can't reach. Anyone should be able to download the pages in this system and port them straight into another markdown system. (Note: the functions of this system aren't included in this - for example, downloading a page will include the table of contents in that page, but the system that generates and updates the ToC isn't included (Subnote: this is opensource, so it sort of is anyway)). This does mean we need a system to include any linked images (optionally?).

## 3. Rules are meant to be broken
Guiding principles are good. Unbreakable rules aren't. If one of these ideas would get in the way of something good working, break that rule.

## 4. Everything is open
The code base for this project is open-source. All documents are viewable by anyone. All documents are editable by anyone. Nothing is hidden. Edit: Actually, the option for password-protected wikis (at maybe a couple levels) would be useful for people.

## 5. No hierarchy
Nothing is stored in folders, sub-folders, or "belongs" to other files. Trying to find which folder something is stored in is annoying, time-consuming, and not necessary with computers. Our file storage is flat, and traversed through search, links, tags, and maybe a graph?

##  6.Tags are key - in moderation
All pages should have relevant tags describing it. Tags are a key way to search for and move through pages/topics. But you *can* have too much of a good thing; if a page has too many tags it shows up everywhere - if too many pages share a tag it becomes harder to find what you need. So pages should have tags that are _relevant_, _descriptive_, and _connective_.

## 7. Tools shouldn't need manuals
Nobody wants to read a guide on how to use something, they want to use the thing. Anyone who uses this project should be able to look at it and know how to use it without instructions or explanation. (What about that big "guide to markdown" page sat in every edit screen? I refer you to principle 3).

## 8. Features are atomic
Every feature should be as small and seperated as possible. Making changes to a feature (or turning it off) should be easy, and shouldn't need digging through massive code files.