<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    
    <xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes" omit-xml-declaration="yes" />
    
    <!-- Ce XSL est largement inspiré de la standardisation sémantique utilisée par Google  -->
    <!-- http://schema.org/docs/full.html -->
    
    <!-- Permet de convertir la casse des caractères  -->
    <!-- Utiliser : <xsl:value-of select="translate($variable, $uppercase, $lowercase)" /> -->
    <xsl:variable name="lowercase" select="'aàâäbcdeéèêëfghiîïjklmnoôöpqrstuùûüvwxyÿz'" />
    <xsl:variable name="uppercase" select="'AÀÂÄBCDEÉÈÊËFGHIÎÏJKLMNOÔÖPQRSTUÙÛÜVWXYŸZ'" />
    
    <!-- Racine du document XML -->
    <xsl:template match="/">
        <xsl:for-each select = "Publishing/Document">
            <xsl:apply-templates select="."></xsl:apply-templates>
        </xsl:for-each>       
    </xsl:template>
    
    <xsl:template match="Document">
        <!-- Récupération des valeurs composants la date -->
        <xsl:variable name="dateISO"><xsl:value-of select="translate(Metadata/ManagementMetadata/PublicationDate/ISO,' ','T')"/></xsl:variable>
        <xsl:variable name="dateDayOfWeek"><xsl:value-of select="Metadata/ManagementMetadata/PublicationDate/Date/DayOfWeek"/></xsl:variable>
        <xsl:variable name="dateShortDate"><xsl:value-of select="Metadata/ManagementMetadata/PublicationDate/Date/ShortDate"/></xsl:variable>
        <xsl:variable name="dateLongDate"><xsl:value-of select="Metadata/ManagementMetadata/PublicationDate/Date/LongDate"/></xsl:variable>
        <xsl:variable name="dateShortTime"><xsl:value-of select="translate(Metadata/ManagementMetadata/PublicationDate/Time/ShortTime, $uppercase, $lowercase)"/></xsl:variable>
        <!-- Assemblage de la date -->
        <xsl:variable name="dateFullDate"><xsl:value-of select="concat($dateDayOfWeek,' ',$dateLongDate,' à ',$dateShortTime)"/></xsl:variable>
        
        
        <!-- Header de l'article -->
        <!--<xsl:element name="header">
            <xsl:attribute name="class">contentHeader</xsl:attribute>-->
            
            <!-- <xsl:element name="h3"><xsl:attribute name="class">section</xsl:attribute><xsl:attribute name="itemprop">articleSection</xsl:attribute><xsl:value-of select="Metadata/ManagementMetadata/Segment"/></xsl:element> -->
            <!--<xsl:element name="h2"><xsl:attribute name="class">title</xsl:attribute><xsl:attribute name="itemprop">name</xsl:attribute><xsl:value-of select="Data/Introduction/Headline"/></xsl:element>-->
            
            <xsl:element name="p">
                <xsl:attribute name="class">teaser</xsl:attribute>
                <xsl:attribute name="itemprop">description</xsl:attribute>
                <xsl:value-of select="Data/Introduction/Heading/Head"/>
            </xsl:element>
            
            <xsl:element name="p">
                <xsl:attribute name="class">meta</xsl:attribute>
                <span class="datePublished">Publié le <xsl:element name="time"><xsl:attribute name="itemprop">datePublished</xsl:attribute><xsl:attribute name="datetime"><xsl:value-of select="$dateISO"/></xsl:attribute><xsl:attribute name="pubdate">pubdate</xsl:attribute><xsl:value-of select="$dateFullDate"/></xsl:element></span>
            </xsl:element>
            
        <!--</xsl:element>-->
        
        <!-- Contenu de l'article -->
        <xsl:element name="section">
            <xsl:attribute name="class">contentBody</xsl:attribute>
            <xsl:attribute name="itemprop">articleBody</xsl:attribute>
            
            <xsl:for-each select = "Data/Sections/Section">
                <xsl:apply-templates select="*[not(self::LinkMetadata) and not(self::MultimediaInserts) and not(self::Files) and not(self::Signature) and not(self::titretable) and not(self::renvoistable) and not(self::Reference)]"/>
            </xsl:for-each>
            
            <xsl:if test = "Data/Sections/Section/Reference">
                <xsl:apply-templates select="Data/Sections/Section/Reference"/>
            </xsl:if>
        </xsl:element>
        


        
        
        <!-- Footer de l'article -->
        <!--<xsl:element name="footer">
            <xsl:attribute name="class">contentFooter</xsl:attribute>-->
            
            <xsl:element name="p">
                <xsl:attribute name="class">meta</xsl:attribute>
                <span class="datePublished">Article du <xsl:element name="time"><xsl:attribute name="datetime"><xsl:value-of select="$dateISO"/></xsl:attribute><xsl:value-of select="$dateShortDate"/></xsl:element></span>
                <xsl:text>&#160;-&#160;</xsl:text>
                <span class="copyright">&#169;&#160;<xsl:element name="span"><xsl:attribute name="itemprop">publisher</xsl:attribute><xsl:value-of select="Metadata/ManagementMetadata/Source"/></xsl:element></span>
                <xsl:text>&#160;-&#160;</xsl:text>
                <span class="copyrightDate"><xsl:element name="time"><xsl:attribute name="datetime"><xsl:value-of select="Metadata/RightsMetadata/Copyright/CopyrightDate"/></xsl:attribute><xsl:value-of select="Metadata/RightsMetadata/Copyright/CopyrightDate"/></xsl:element></span>
            </xsl:element>
            
        <!--</xsl:element>-->
    </xsl:template>
    
    <!-- TEMPLATES DU CONTENU -->
    
    <xsl:template match="Text">
        <xsl:choose>
            <xsl:when test="starts-with(.,'A lire également :')"></xsl:when>
            <!-- 
            <xsl:when test="starts-with(.,'*')">
                <p class="commentaire"><xsl:apply-templates/></p>
            </xsl:when> 
            -->
            <!-- Exclusion des annotations -->
            <xsl:when test="Annotation">
                <xsl:apply-templates/>
            </xsl:when>
            <xsl:otherwise>
                <p><xsl:apply-templates/></p>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
    
    <xsl:template match = "titreparagraphe">
        <xsl:if test="(. != '')">
            <h5 class="title"><xsl:apply-templates/></h5>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match = "RefChapitre | Reference">
        <xsl:if test="(. != '')">
            <cite class="reference"><xsl:apply-templates/></cite>
        </xsl:if>
    </xsl:template>
    
    <!-- TEMPLATES DES ANNOTATIONS -->
    
    <xsl:template match="Annotation">
        <section class="annotation">
            <xsl:apply-templates/>
        </section>
    </xsl:template>
    
    <xsl:template match="TitreAnnotation">
        <h6 class="titreAnnotation"><xsl:value-of select="."/></h6>
    </xsl:template>
    
    <xsl:template match = "TexteAnnotation">
        <xsl:if test="(. != '')">
            <p class="texteAnnotation"><xsl:apply-templates/></p>
        </xsl:if>
    </xsl:template>
    
    <!-- TEMPLATES DES ENCADRÉS -->
    
    <xsl:template match="Encadre">
        <section class="encadre">
            <xsl:apply-templates/>
        </section>
    </xsl:template>
    
    <xsl:template match="EncadreTitre">
        <h6 class="encadreTitre"><xsl:value-of select="."/></h6>
    </xsl:template>
    
    <xsl:template match="EncadreTexte">
        <p class="encadreTexte"><xsl:apply-templates/></p>
    </xsl:template>
    
    <!-- TEMPLATES DE PRESENTATION -->
    
    <xsl:template match = "ExternalLink">
        <xsl:element name="a"><xsl:attribute name="href"><xsl:value-of select="URL"/></xsl:attribute><xsl:attribute name="class">target-blank</xsl:attribute><xsl:value-of select = "LinkText"/></xsl:element>
    </xsl:template>
    
    <xsl:template match = "BR">
        <br/>
    </xsl:template>
    
    <xsl:template match = "Sup">
        <xsl:if test="(. != '')">       
            <sup><xsl:value-of select = "."/></sup>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match = "B">
        <xsl:if test="(. != '')">          
            <b><xsl:value-of select = "."/></b>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match = "I">
        <xsl:if test="(. != '')">
            <i><xsl:value-of select = "."/></i>
        </xsl:if>
    </xsl:template>
    
    <!-- TEMPLATES DES TABLEAUX -->
    
    <xsl:template name="for.loop">
        <xsl:param name="tableau" />
        <xsl:param name="max" />
        <xsl:param name="i" />
        <xsl:param name="count" />
        
        <!-- on affiche le texte à la fin de la boucle-->
        <xsl:if test="$i = $count">
            <xsl:element name="tfoot">
                <xsl:element name="tr">
                    <xsl:element name="td">
                        <xsl:attribute name="class">valign-middle textalign-center</xsl:attribute>
                        <xsl:attribute name="colspan"><xsl:value-of select="$max"/></xsl:attribute>
                        <xsl:apply-templates select="../renvoistable"/>
                    </xsl:element>
                </xsl:element>
            </xsl:element>
        </xsl:if>
        
        <!-- répétition de la boucle -->
        <xsl:if test="$i &lt;= $count">
            <xsl:call-template name="for.loop">
                <xsl:with-param name="tableau">
                    <xsl:value-of select="$tableau"/>
                </xsl:with-param>
                
                <xsl:with-param name="max">
                    <xsl:choose>
                        <xsl:when test="count($tableau/tr[$i + 1]/td) &gt;= $max">
                            <xsl:value-of select="count($tableau/tr[$i + 1]/td)"/>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="$max"/>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:with-param>
                
                <xsl:with-param name="i">
                    <xsl:value-of select="$i + 1"/>
                </xsl:with-param>
                
                <xsl:with-param name="count">
                    <xsl:value-of select="$count"/>
                </xsl:with-param>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match="titretable">
        <xsl:if test="(. != '')">
            <caption><span class="caption"><xsl:apply-templates/></span></caption>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match="renvoistable">
        <xsl:if test="(. != '')">
            <xsl:apply-templates/>
        </xsl:if>
    </xsl:template>
    
    <xsl:template match="table">	
        <xsl:if test="/">
            <xsl:element name="table">
                <xsl:if test="../titretable">
                    <xsl:apply-templates select="../titretable" />
                </xsl:if>
                <xsl:if test="../renvoistable">
                    <xsl:call-template name="for.loop">
                        <xsl:with-param name="tableau"><xsl:value-of select="."/></xsl:with-param>
                        <xsl:with-param name="max"><xsl:value-of select="count(./tr[1]/td)"/></xsl:with-param>
                        <xsl:with-param name="i">1</xsl:with-param>
                        <xsl:with-param name="count"><xsl:value-of select="count(./tr)"/></xsl:with-param>
                    </xsl:call-template>
                </xsl:if>
                <xsl:element name="tbody">
                    <xsl:for-each select="tr">
                        <xsl:element name="tr">
                            <xsl:for-each select="td">
                                <xsl:element name="td">
                                    <xsl:if test="@class != '' or @valign != '' or @align != ''">
                                        <xsl:attribute name="class">
                                            <xsl:if test="@class != ''">
                                                <xsl:value-of select="@class"/>
                                            </xsl:if>
                                            <xsl:choose>
                                                <xsl:when test="@valign = 'top'"> valign-top</xsl:when>
                                                <xsl:when test="@valign = 'middle'"> valign-middle</xsl:when>
                                                <xsl:when test="@valign = 'bottom'"> valign-bottom</xsl:when>
                                                <xsl:otherwise> valign-middle</xsl:otherwise>
                                            </xsl:choose>
                                            <xsl:choose>
                                                <xsl:when test="@align = 'left'"> textalign-left</xsl:when>
                                                <xsl:when test="@align = 'center'"> textalign-center</xsl:when>
                                                <xsl:when test="@align = 'right'"> textalign-right</xsl:when>
                                                <xsl:otherwise> textalign-center</xsl:otherwise>
                                            </xsl:choose>
                                        </xsl:attribute>
                                    </xsl:if>
                                    <xsl:if test="@rowspan != ''">
                                        <xsl:attribute name="rowspan"><xsl:value-of select="@rowspan"/></xsl:attribute>
                                    </xsl:if>
                                    <xsl:if test="@colspan != ''">
                                        <xsl:attribute name="colspan"><xsl:value-of select="@colspan"/></xsl:attribute>
                                    </xsl:if>
                                    <xsl:apply-templates/>
                                </xsl:element>
                            </xsl:for-each>
                        </xsl:element>
                    </xsl:for-each>
                </xsl:element>
            </xsl:element>
        </xsl:if>
    </xsl:template>
    
    <!-- 
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
    -->
    
    <!--
        <xsl:template match = "SubTitle"> 
        <h3><xsl:value-of select="."/></h3>
        </xsl:template>
    -->
    
</xsl:stylesheet>