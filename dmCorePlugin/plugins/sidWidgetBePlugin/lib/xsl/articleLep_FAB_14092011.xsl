<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:output method="html" version="4.0" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <style type="text/css">
           a
            {
                text-decoration: none;
                color: #444444;
            }
            
			td
            {
            font-size : 12px; 
			background: #cccccc;
            }
            .titre_article
            {
                font-size : 20px;
                font-weight : bold;
                color: red;
            }
            .chapeau
            {
            font-size : 12px;
            font-weight : bold;
            color: yellow;
            }
            .sous_titre
            {
            font-size : 13px;
            font-weight : bold;
            color: pink;
            }
            .texte
            {
            font-size : 12px;
            color: blue;
            } 
            .commentaire
            {
            font-size : 11px;
            color: black;
            font-style:italic;
            }
            .signature
            {
            font-size : 11px;
            font-weight : bold;            
            text-align : right; 
            color: green;
            }
            .titreannotation
            {
            font-size : 12px;
            font-weight : bold;
            color: blue;
            }
            .texteannotation
            {
            font-size : 12px;
            font-style:italic;
            color: blue;
            }
			.encadre
			{
			background: #bbbbbb;
			}
			.titreencadre
            {
            font-size : 12px;
            font-weight : bold;
            color: blue;
            }
            .texteencadre
            {
            font-size : 12px;
            font-style:italic;
            color: blue;
            }
            .titretableau
            {
            background: #bbbbbb;
            font-weight : bold;
            text-align: center;
            }
			.texte2
            {
            background: #ffffff;
            text-align: center;
            }
			.texte3
            {
            background: #eeeeee;
            font-weight : bold;
            text-align: center;
            }
        </style>
        
        <xsl:for-each select = "Publishing/Document/Data">
            <!--    Photo de l'article   -->
            <p><xsl:variable name="id_photo" select = "../Code"/>
            <img src="/_images/lea{$id_photo}-g.jpg"/></p>
            <!--    Titre de l'article   -->
            <p class="titre_article"><xsl:value-of select = "Introduction/Headline"/></p>
            <!--    Chapeau de l'article   -->
            <p class="chapeau"><xsl:value-of select = "Introduction/Heading/Head"/></p>
            
            <!--    Contenu de l'article   -->
            <xsl:for-each select = "Sections/Section">
                <xsl:apply-templates select="*[not(self::MultimediaInserts) and not(self::Files)]"/>
            </xsl:for-each>
        </xsl:for-each>
    </xsl:template>
      
      
    <xsl:template match="Text">
           <xsl:choose>
               <xsl:when test="starts-with(.,'A lire également :')"></xsl:when>
               <xsl:when test="starts-with(.,'*')">
                   <p class="commentaire"><xsl:apply-templates/></p>
               </xsl:when>  
               <xsl:otherwise>
                   <p class="texte">
                       <xsl:apply-templates/>
                   </p>
               </xsl:otherwise>
           </xsl:choose>           
    </xsl:template>
	
	<xsl:template match="Annotation">
        <p class="texteannotation">
            <xsl:apply-templates/>
        </p>
    </xsl:template>

    <xsl:template match="TitreAnnotation">
        <span class="titreannotation">
            <xsl:value-of select="."/>
        </span>
    </xsl:template>

    <xsl:template match="TexteAnnotation">
        <xsl:apply-templates/>
    </xsl:template>
	
	<xsl:template match="Encadre">
        <div class="encadre">
            <xsl:apply-templates/>
        </div>
    </xsl:template>

    <xsl:template match="EncadreTitre">
        <span class="titreencadre">
            <xsl:value-of select="."/>
        </span>
    </xsl:template>

    <xsl:template match="EncadreTexte">
	<span class="texteencadre">
        <xsl:apply-templates/>
		</span>
    </xsl:template>
    
    <xsl:template match = "SubTitle"> 
        <p class="sous_titre"><xsl:value-of select="."/></p>
    </xsl:template>
    
    <xsl:template match = "ExternalLink"> 
        <xsl:variable name="URL" select="URL"/>
       <a href="{$URL}" target="_blank"><xsl:value-of select = "LinkText"/></a>
    </xsl:template>
    
    <xsl:template match = "BR"> 
        <br/>
    </xsl:template>
    
    <xsl:template match = "B"> 
        <b><xsl:apply-templates/></b>
    </xsl:template>
    
    <xsl:template match = "I"> 
        <i><xsl:apply-templates/></i>
    </xsl:template>

    <xsl:template match = "Sup">            
        <sup><xsl:value-of select = "."/></sup>
    </xsl:template>
    
    <xsl:template match = "Signature"> 
        <p class="signature"><xsl:value-of select="."/></p>
    </xsl:template>
       
    <!-- TEMPLATES DES TABLEAUX -->


    <xsl:template match="titretable">
        <div align="center" class="titretableau">
            <b>
                <xsl:apply-templates/>
            </b>
        </div>
    </xsl:template>

    <xsl:template match="table">
        <xsl:if test="/">
            <div align="center">
                <table border="0" cellspacing="1" cellpadding="2">
                    <xsl:for-each select="tr">
                        <tr height="{@height}">
                            <xsl:for-each select="td">
                                <td width="{@width}" class="{@class}" align="{@align}"
                                    valign="{@valign}" rowspan="{@rowspan}" colspan="{@colspan}">
                                    <xsl:apply-templates/>
                                </td>
                            </xsl:for-each>
                        </tr>
                    </xsl:for-each>
                </table>
                <xsl:if test="../renvoistable">
                    <table>
                        <tr>
                            <td class="textesans10" align="justify">
                                <xsl:apply-templates select="../renvoistable"/>
                            </td>
                        </tr>
                    </table>
                </xsl:if>
            </div>
        </xsl:if>
    </xsl:template>
    
</xsl:stylesheet>