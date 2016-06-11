<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns="http://www.w3.org/1999/xhtml"
    exclude-result-prefixes="html"
    xmlns:xalan="http://xml.apache.org/xslt"
>
	<xsl:output
        method="xml"
        doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
        doctype-public="-//W3C//DTD XHTML 1.1//EN"
        indent="yes"
    />

	<xsl:template match="/list">
		<list>
			<xsl:apply-templates/>
		</list>
	</xsl:template>

	<xsl:template match="charter">
		<document>
			<xsl:apply-templates/>
		</document>
	</xsl:template>

	<xsl:template match="chDesc/head/idno">
		<title><xsl:value-of select="."/></title>
	</xsl:template>

	<xsl:template match="chDesc/head/issued/issueDate/date">
		<date><xsl:value-of select="."/></date>
	</xsl:template>

	<xsl:template match="chDesc/head/issued/issuePlace/placeName">
		<place><xsl:value-of select="."/></place>
	</xsl:template>

	<xsl:template match="chDesc/relevantPersonal/issuer/persName">
		<issuer><xsl:value-of select="."/></issuer>
	</xsl:template>

	<xsl:template match="chDesc/abstract">
		<abstract><xsl:value-of select="."/></abstract>
	</xsl:template>

	<xsl:template match="text()|@*">
	</xsl:template>	

</xsl:stylesheet>
