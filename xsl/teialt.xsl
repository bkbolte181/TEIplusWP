<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
	xmlns:eg="http://www.tei-c.org/ns/Examples"
	xmlns:tei="http://www.tei-c.org/ns/1.0" 
	xmlns:xd="http://www.oxygenxml.com/ns/doc/xsl" 
	xmlns:exsl="http://exslt.org/common"
	xmlns:msxsl="urn:schemas-microsoft-com:xslt"
	xmlns:fn="http://www.w3.org/2005/xpath-functions"
	extension-element-prefixes="exsl msxsl"
	xmlns="http://www.w3.org/1999/xhtml" 
	exclude-result-prefixes="xsl tei xd eg fn #default">
	<xd:doc  scope="stylesheet">
		<xd:desc>
			<xd:p><xd:b>Created on:</xd:b> Nov 17, 2011</xd:p>
			<xd:p><xd:b>Author:</xd:b> John A. Walsh</xd:p>
			<xd:p>TEI Boilerplate stylesheet: Copies TEI document, with a very few modifications
				into an html shell, which provides access to javascript and other features from the
				html/browser environment.</xd:p>
		</xd:desc>
	</xd:doc>

	<xsl:template match="*">
        <div>
            <xsl:attribute name="class">tei-<xsl:value-of select="name(.)"/></xsl:attribute>
            <xsl:attribute name="rel">tei</xsl:attribute>
            <xsl:apply-templates />
            <xsl:comment> #<xsl:value-of select="name(.)"/> </xsl:comment>
        </div>
	</xsl:template>
	
</xsl:stylesheet>
