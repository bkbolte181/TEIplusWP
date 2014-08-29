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
	var pagingControls = 'Go to page: <select onchange="pager.showPage(this.value);">';
	for (var i = 1; i <= numPages; i++) {
	    if (i != currentPage) {
		pagingControls += '<option value='+i+'>' + i + '</option>';
	    } else {
		pagingControls += '<option selected>' + i + '</option>';
	    }
	}

	pagingControls += '</select>';

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
    // Needs an update: Old one wasn't working.
    // Right now it just opens the image in a new window.
    facsWindow = window.open("about:blank")
    facsWindow.document.write("<img src='wp-content/plugins/TEIplusWP/images/"+url+"'>")
    facsWindow.document.close()
}
