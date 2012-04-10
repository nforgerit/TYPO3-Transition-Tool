<xsl:when test="fieldlist/field[@index='CType']/text() = 'text'">
    <xsl:element name="node">
        <xsl:attribute name="type">TYPO3.TYPO3:Text</xsl:attribute>
        <xsl:attribute name="nodeName">
            <xsl:value-of select="fieldlist/field[@index='header']/text()"/>
        </xsl:attribute>
        <xsl:attribute name="locale">en_EN</xsl:attribute>

        <xsl:element name="properties">
            <xsl:element name="headline">
                <xsl:value-of select="fieldlist/field[@index='header']/text()"/>
            </xsl:element>
            <xsl:element name="text">
                <xsl:value-of select="fieldlist/field[@index='bodytext']/text()"/>
            </xsl:element>
        </xsl:element>
    </xsl:element>
</xsl:when>