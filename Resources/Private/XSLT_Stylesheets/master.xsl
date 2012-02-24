<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" 
	version="1.0" encoding="UTF-8" 
	omit-xml-declaration="no" indent="yes"/>

<xsl:template match="/">
	<!--###PRE_TRANSFORM_HOOKS###-->
	<xsl:apply-templates select="T3RecordDocument"/>
	<!--###POST_TRANSFORM_HOOKS###-->      
</xsl:template>     

<xsl:template match="T3RecordDocument">
	<root>
		<site uuid="" nodeName="">
			<properties>
				<name><xsl:value-of select="//field[@index = 'sitetitle']"/></name>
				<state>1</state>
			</properties>

			<!-- root node -->
			<node uuid="" type="TYPO3:Page" nodeName="homepage" locale="en_EN">
				<properties>
					<title>Home</title>
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

	<xsl:text disable-output-escaping="yes">&#xa;&#9;&lt;</xsl:text>
	<xsl:value-of select="name()"/>  
		<xsl:text> uuid=""</xsl:text>
		<xsl:text> type=""</xsl:text>
		<xsl:text> nodeName=""</xsl:text>
		<xsl:text> locale=""</xsl:text>   
	<xsl:text disable-output-escaping="yes">&gt;&#xa;</xsl:text>
		<xsl:text>&#xa;&#9;</xsl:text>         
		
<!-- (begin) node's contents -->		
		<xsl:if test="$uid &gt; 0">
    	<xsl:call-template name="record">
				<xsl:with-param name="uid" select="$uid"/>
			</xsl:call-template>  
		</xsl:if>                  
		
		<xsl:apply-templates select="node"/>
<!-- (end) node's contents -->

	<xsl:text disable-output-escaping="yes">&#xa;&#9;&lt;/</xsl:text>
	<xsl:value-of select="name()"/>
	<xsl:text disable-output-escaping="yes">&gt;&#9;&#xa;</xsl:text>     
</xsl:template>     

<xsl:template name="record">
	<xsl:param name="uid"/>        
	  
	<xsl:if test="$uid &gt; 0">    
				
	  <properties>
			
			<!-- render headline -->
			<xsl:if test="/T3RecordDocument/header/records/table/rec[uid = $uid]/title">
				<headline>
					<xsl:value-of select="/T3RecordDocument/header/records/table/rec[uid = $uid]/title"/>
				</headline>
			</xsl:if>          
			
			<!-- render bodytext --> 			
			<xsl:if test="/T3RecordDocument/records/tablerow[fieldlist/field = $uid]/fieldlist/field[@index='bodytext']">      			
				<xsl:text disable-output-escaping="yes">&#xa;&#9;&lt;text&gt;&lt;&#33;&#91;CDATA&#91;&#xa;</xsl:text>      
						<xsl:value-of select="/T3RecordDocument/records/tablerow[fieldlist/field = $uid]/fieldlist/field[@index='bodytext']"/>
 				<xsl:text disable-output-escaping="yes">&#xa;&#9;&#93;&#93;&gt;&lt;/text&gt;</xsl:text>
			</xsl:if>
			<!--<xsl:apply-templates select="/T3RecordDocument/records/tablerow[fieldlist/field[@index = 'uid']] = $uid">
				<xsl:with-param name="uid" select="$uid"/>
			</xsl:apply-templates>-->

		</properties>
	</xsl:if>
</xsl:template>       


<xsl:template name="tablerow">	   
	<xsl:param name="uid"/>
	called tablerow       
        	
	<xsl:if test="count(fieldlist/field[@bodytext])">
		<text>
			<![CDATA[
				<xsl:value-of select="fieldlist/field[@bodytext]"/>
			]]>
		</text>
	</xsl:if>
</xsl:template>


</xsl:stylesheet>