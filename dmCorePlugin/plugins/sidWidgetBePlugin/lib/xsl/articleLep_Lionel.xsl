<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF8" />

    <xsl:template match="/">
        <xsl:apply-templates select="Publishing"/>
    </xsl:template>

    <xsl:template match="Publishing">
        <xsl:apply-templates select="Document"/>
    </xsl:template>

    <xsl:template match="Document">
        <xsl:apply-templates select="Data" />
        <xsl:apply-templates select="Metadata" />
    </xsl:template>

    <xsl:template match="Metadata">
        <xsl:apply-templates select="AdministrativeMetadata"/>
    </xsl:template>

    <xsl:template match="AdministrativeMetadata">
        <p>Source:
        <b><xsl:value-of select="Source"/></b>
        </p>
    </xsl:template>

<!--
    <xsl:template match="Data">
        <xsl:apply-templates select="Introduction"/>
        <xsl:apply-templates select="Sections/Section/MultimediaInserts"/>
        <xsl:apply-templates select="Sections"/>
    </xsl:template>
-->

    <xsl:template match="Introduction">
       <!-- <h1 style="color:black">
            <xsl:value-of select="Headline"/>
        </h1>-->
        <p style="color:#F4F">
      <!--  <xsl:value-of select="Heading/Head"/>-->
        </p>
    </xsl:template>

    <xsl:template match="Sections">
        <xsl:apply-templates select="Section"/>
    </xsl:template>

    <xsl:template match="Section">
        <xsl:apply-templates select="*[not(self::MultimediaInserts)]"/>
    </xsl:template>

   <xsl:template match="Text">
        <p style="color:black">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>

    <xsl:template match="Text/B">
        <p style="color:blue">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>

    <xsl:template match="SubTitle">
        <p style="color:green">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>

    <xsl:template match="Signature">
        <p style="color:black">
            <xsl:value-of select="."/>
        </p>
    </xsl:template>

<!--
    <xsl:template match="MultimediaInserts">
        <xsl:element name="img">
            <xsl:attribute name="src">#routeImagesDir#<xsl:value-of select="MultimediaInsert/FileName"/>
            </xsl:attribute>
        </xsl:element>
    </xsl:template>
-->
</xsl:stylesheet>


