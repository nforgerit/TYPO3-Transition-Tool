<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

	<xsl:output
			method="xml"
			version="1.0"
			encoding="UTF-8"
			omit-xml-declaration="no"
			indent="yes"
			cdata-section-elements="source text"
			/>

	<xsl:template match="/">
		<!--###PRE_TRANSFORM_HOOKS###-->
		<xsl:apply-templates select="T3RecordDocument"/>
		<!--###POST_TRANSFORM_HOOKS###-->
	</xsl:template>

	<xsl:template match="T3RecordDocument">
		<root>
			<site identifier="" nodeName="home">
				<properties>
					<name><!-- ###SITE_NAME### --></name>
					<state>2</state>
					<siteResourcesPackageKey><!-- ###PACKAGE_KEY### --></siteResourcesPackageKey>
				</properties>

				<!-- root node -->
				<node identifier="" type="TYPO3.Neos:Page" nodeName="homepage" locale="en_EN">
					<properties>
						<title>
							<xsl:value-of
									select="/T3RecordDocument/records/tablerow[@index='pages' and @id='1']/fieldlist/field[@index='title']/text()"/>
						</title>
					</properties>

					<xsl:apply-templates select="header/pagetree"/>
				</node>

			</site>
		</root>
	</xsl:template>

	<xsl:template match="pagetree">
		<xsl:apply-templates select="node"/>
	</xsl:template>

	<xsl:template match="node">
		<xsl:param name="uid" select="uid/text()"/>

		<!-- (begin) render page with its attributes -->
		<xsl:element name="node">
			<xsl:attribute name="identifier"/>
			<xsl:attribute name="type">TYPO3.Neos:Page</xsl:attribute>
			<xsl:attribute name="nodeName">
				<!-- this value needs to match "/[-a-zA-Z0-9]+/" -->
				<xsl:value-of
						select="/T3RecordDocument/records/tablerow[@index='pages' and @id=$uid]/fieldlist/field[@index='title']/text()"
						disable-output-escaping="no"/>
			</xsl:attribute>
			<xsl:attribute name="locale">en_EN</xsl:attribute>
			<!-- (end) render page with its attributes -->

			<!-- (begin) page's/node's contents -->
			<xsl:if test="count(/T3RecordDocument/records/tablerow[@index='tt_content' and fieldlist/field[@index='pid']/text()=$uid]) > 0">
				<xsl:element name="node">
					<xsl:attribute name="identifier"/>
					<xsl:attribute name="type">TYPO3.Neos:Content</xsl:attribute>
					<xsl:attribute name="nodeName">
						<xsl:value-of select="concat(/T3RecordDocument/records/tablerow[@index='pages' and @id=$uid]/fieldlist/field[@index='title']/text(), '-section')"/>
					</xsl:attribute>
					<xsl:attribute name="locale">en_EN</xsl:attribute>

					<xsl:element name="properties">
						<xsl:element name="title">
							<xsl:value-of
									select="concat(/T3RecordDocument/records/tablerow[@index='pages' and @id=$uid]/fieldlist/field[@index='title']/text(), '-section')"/>
						</xsl:element>
					</xsl:element>

					<xsl:for-each
							select="/T3RecordDocument/records/tablerow[@index='tt_content' and fieldlist/field[@index='pid']/text()=$uid]">
						<xsl:call-template name="tablerow"/>
					</xsl:for-each>
				</xsl:element>
			</xsl:if>
			<!-- (end) page's/node's contents -->

			<!-- (begin) recursively call <node> -->
			<xsl:apply-templates select="node"/>
			<!-- (end) recursively call <node> -->
		</xsl:element>
	</xsl:template>

	<xsl:template name="tablerow">
		<xsl:choose>
			###SNIPPET_MARKER###
		</xsl:choose>
	</xsl:template>

</xsl:stylesheet>
