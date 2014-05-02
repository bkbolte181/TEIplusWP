#README v 4.06

This is an in-progress plugin for Wordpress. Adding it to your site impliments some features that merge TEI Boilerplate, a tool written to easily display TEI documents, with Wordpress, allowing you to integrate a file into a post. NOTE: If you are using a Wordpress.com site, you cannot install plugins (unless you are a VIP user). To install plugins, you need to have Wordpress installed on a different server.

#Managing Files
After uploading it to your site and enabling it (through the plugins menu), an option should now be visible on the Dashboard in the Plugins tab called "TEI + WP". It should be right below the Editor option. Clicking on this will take you to the plugin's admin page. Here you can see all the documents that are currently on the server. You can upload and delete files from this page.

#Integrating a File into a Post
Upload your file through the TEI + WP plugin admin page. Remember the name of this file, and try naming it something short yet identifiable. Avoid spaces in the name. For instance, one of the files I uploaded is called red-diary.xml, and is a transcript of Thomas Merton's Red Diary. Once you've uploaded a file, start writing a new post. When you want to display your file, write the command:

     @teipluswp:red-diary.xml

Substitute your file's name into the last bit. If you look at your page, the XML document will be displayed.

#Pagination
Right now TEIplusWP recognizes divs as pages (could be fized in the future). To make a document paginate, add the tag

     <paginate/>
     
right after the TEI tag. If the document doesn't have this tag, it will just display as it normally would.

Anyways, hope it works alright. It's still a work in progress. E-mail me at b.k.bolte@emory.edu with suggestions.
