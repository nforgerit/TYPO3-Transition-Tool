<xsl:otherwise>
	<xsl:message terminate="no">
		damn! could not render tablerow #<xsl:value-of select="fieldlist/field[@index='uid']/text()"/>:(
	</xsl:message>
</xsl:otherwise>