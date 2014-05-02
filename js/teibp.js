//If W3C event model used, prefer that. Window events are fallbacks
if(document.addEventListener){
    //W3C event model used
    document.addEventListener("DOMContentLoaded", init, false);
    window.addEventListener("load", init, false);
} else if(document.attachEvent){
    //IE event model used
    document.attachEvent( "onreadystatechange", init);
    window.attachEvent( "onload", init);
}

// Paginator from http://www.script-tutorials.com/how-to-create-easy-pagination-with-jquery/
var Imtech = {}
Imtech.Pager = function() {
    this.paragraphsPerPage = 3;
    this.currentPage = 1;
    this.pagingControlsContainer = '#pagingControls';
    this.pagingContainerPath = '#tei_wrapper';

    this.numPages = function() {
	var numPages = 0;
	if (this.paragraphs != null && this.paragraphsPerPage != null) {
	    numPages = Math.ceil(this.paragraphs.length / this.paragraphsPerPage);
	}
	return numPages;
    };

    this.showPage = function(page) {
	this.currentPage = page;
	var html = '';
	
	this.paragraphs.slice((page-1) * this.paragraphsPerPage,
			      ((page-1) * this.paragraphsPerPage)
			      + this.paragraphsPerPage).each(function() {
				  html += '<div>' + jQuery(this).html() + '</div>';
			      });
	jQuery(this.pagingContainer).html(html);
	renderControls(this.pagingControlsContainer, this.currentPage, this.numPages());
    }
    
    var renderControls = function(container, currentPage, numPages) {
	var pagingControls = 'Page: <ul>';
	for (var i = 1; i <= numPages; i++) {
	    if (i != currentPage) {
		pagingControls += '<li><a href="#" onclick="pager.showPage(' + i + '); return false;">' + i + '</a></li>';
	    } else {
		pagingControls += '<li>' + i + '</li>';
	    }
	}

	pagingControls += '</ul>';

	jQuery(container).html(pagingControls);
    }
}

function init() {
    // empty
}

function switchThemes(theme) {
	document.getElementById('maincss').href=theme.options[theme.selectedIndex].value;
}

function showFacs(num, url, id) {
	facsWindow = window.open ("about:blank")
	facsWindow.document.write("<html>")
	facsWindow.document.write("<head>")
	facsWindow.document.write("<title>TEI Boilerplate Facsimile Viewer</title>")
	facsWindow.document.write($('#maincss')[0].outerHTML)
	facsWindow.document.write($('#customcss')[0].outerHTML)
	facsWindow.document.write("<link rel='stylesheet' href='../js/jquery-ui/themes/base/jquery.ui.all.css'>")
	if ($('#teibp-tagusage-css').length) {
	  facsWindow.document.write($('#teibp-tagusage-css')[0].outerHTML)
	}
	if ($('#teibp-rendition-css').length) {
	  facsWindow.document.write($('#teibp-rendition-css')[0].outerHTML)
	}
	facsWindow.document.write("<script type='text/javascript' src='../js/jquery/jquery.min.js'></script>")
	facsWindow.document.write("<script type='text/javascript' src='../js/jquery-ui/ui/jquery-ui.js'></script>")
	facsWindow.document.write("<script type='text/javascript' src='../js/jquery/plugins/jquery.scrollTo-1.4.3.1-min.js'></script>")
	facsWindow.document.write("<script type='text/javascript' src='../js/teibp.js'></script>")
	facsWindow.document.write("<script type='text/javascript'>")
	facsWindow.document.write("$(document).ready(function() {")
	facsWindow.document.write("$('.facsImage').scrollTo($('#" + id + "'))")
	facsWindow.document.write("})")
	facsWindow.document.write("</script>")
	facsWindow.document.write("<script type='text/javascript'>	$(function() {$( '#resizable' ).resizable();});</script>")
	facsWindow.document.write("</head>")
	facsWindow.document.write("<body>")
	facsWindow.document.write($("teiHeader")[0].outerHTML)
	facsWindow.document.write("<div id='resizable'>")
	facsWindow.document.write("<div class='facsImage'>")
	$(".-teibp-thumbnail").each(function() {
		facsWindow.document.write("<img id='" + $(this).parent().parent().parent().attr('id') + "' src='" + $(this).attr('src') + "' alt='facsimile page image'/>")
	})
	facsWindow.document.write("</div>")
	facsWindow.document.write("</div>")
	facsWindow.document.write($("footer")[0].outerHTML)

	facsWindow.document.write("</body>")
	facsWindow.document.write("</html>")
	facsWindow.document.close()
}