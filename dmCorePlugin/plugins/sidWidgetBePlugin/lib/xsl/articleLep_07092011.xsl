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
            .titretableau
            {
            background: #cccccc;
            font-weight : bold;
            text-align: center;
            }
        </style>
        
        <xsl:for-each select = "Publishing/Document/Data">
            <!--    Photo de l'article   -->
            <p><xsl:variable name="id_photo" select = "../Code"/>
            <img src="/_images/lea{$id_photo}.jpg"/></p>
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
               <xsl:when test="contains(.,'Attention :') or contains(.,'Remarque :') or contains(.,'En pratique :') or contains(.,'Rappel :') or contains(.,'Important :') or contains(.,'Précision :') or contains(.,'Précisions :') or contains(.,'À noter :')">
                  <p><span class="titreannotation"><xsl:value-of select="B"/></span>
                      <span class="texteannotation"><xsl:apply-templates select="text()"/></span></p>                   
               </xsl:when>
               <xsl:otherwise>
                   <p class="texte">
                       <xsl:apply-templates/>
                   </p>
               </xsl:otherwise>
           </xsl:choose>           
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
       
    <xsl:template match="Table">
        <xsl:if test="/">
            <div align="center">
                <xsl:for-each select="TableLines">
                    <table border="1" width="80%">
                    <tbody>
                       	 <xsl:for-each select="TableLine">             						
		<xsl:if test="position() = 1"> 		                  
                                    <tr class="titretableau">
                                          <td><xsl:apply-templates/></td>
                                    </tr>
		</xsl:if>							
                       	 </xsl:for-each>                                              
                        <xsl:for-each select="TableLine">   
                            <xsl:if test="position() > 1">                            
		        <tr>
		            <td><xsl:apply-templates/></td>
		        </tr>	            
                            </xsl:if>						
                        </xsl:for-each>  
		
                    </tbody>
                </table>
             
                    </xsl:for-each>
            </div>
        </xsl:if>
    </xsl:template>

<xsl:template match = "Tabulation"> 
    <xsl:if test=".">
    <td><xsl:apply-templates/></td>
    </xsl:if>
</xsl:template> 
    
</xsl:stylesheet>