<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="iso-8859-15" omit-xml-declaration="yes"/>
    <xsl:template match="//Document">
        <xsl:variable name="id_import">
            <xsl:for-each select="//Element/Metas/Meta">
                <xsl:if test="@code='cle'">
                    <xsl:value-of select="."/>
                </xsl:if>
            </xsl:for-each>
        </xsl:variable>

        <article>
            <xsl:attribute name="idArticle">
                <xsl:choose>
                    <xsl:when test="$id_import!=''">
                        <xsl:value-of select="$id_import"/>
                    </xsl:when>
                    <xsl:otherwise>lea<xsl:value-of select="//Element/Id"/>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
            <xsl:attribute name="auteurArticle">
                <xsl:value-of select="//Element/Signature"/>
            </xsl:attribute>
            <xsl:attribute name="dateArticle">
                <xsl:call-template name="dateArticle">
                    <xsl:with-param name="date">
                        <xsl:value-of select="//Element/DateCreation"/>
                    </xsl:with-param>
                </xsl:call-template>
            </xsl:attribute>

            <xsl:attribute name="rubrique">
                <xsl:call-template name="rubrique"/>
            </xsl:attribute>

            <copyright> Copyright SID Presse - <xsl:value-of
                    select="substring(//Element/DateCreation,1,4)"/></copyright>
            <xsl:variable name="ref">
                <xsl:for-each select="//Element/Metas/Meta">
                    <xsl:if test="@code='subject'">
                        <xsl:value-of select="."/>
                    </xsl:if>
                </xsl:for-each>
            </xsl:variable>
            <titrearticle>
                <xsl:choose>
                    <xsl:when test="normalize-space($ref)!=''">
                        <xsl:value-of select="normalize-space($ref)"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="//Element/Titre"/>
                    </xsl:otherwise>
                </xsl:choose>
            </titrearticle>
            <chapitre>
                <titrechapitre>
                    <xsl:value-of select="//Element/Chapeau"/>
                </titrechapitre>
                <refchapitre>

                    <xsl:for-each select="//Element/Texte/div">
                        <xsl:if test="string(@class)='reference'">
                            <xsl:value-of select="."/>
                        </xsl:if>
                    </xsl:for-each>
                </refchapitre>


                <xsl:for-each select="//Medias/Media[Label/p='ART:PRINCIPALE']">
                    <xsl:if test="position()=1">
                        <photo>
                            <xsl:attribute name="src">
                                <xsl:value-of select="Fichier"/>
                            </xsl:attribute>
                        </photo>
                    </xsl:if>
                </xsl:for-each>
                <contenu>
                    <paragraphe>
                        <xsl:apply-templates select="//Element/Texte"/>
                    </paragraphe>
                </contenu>
            </chapitre>
        </article>

    </xsl:template>
    <xsl:template match="Texte">
        <xsl:apply-templates/>
    </xsl:template>
    <xsl:template name="rubrique">

        <xsl:variable name="retour">
            <xsl:for-each select="//Rubriques/Rubrique">

                <!--
<xsl:if test="string(@path)='tpepme:www:gestion:juridique:news'">juridique,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:gestion:social:news'">social,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:patrimoine:patrimoine:news'">patrimoine,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:gestion:fiscalite:news'">fiscal,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:gestion:droit-des-societes:news'">droits_societes,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:creation:creation:news'">creation,</xsl:if>
<xsl:if test="string(@path)='tpepme:www:developper:multimedia:news'">multimedia,</xsl:if>
-->

                <xsl:if test="contains(@path,'fiscal')=true()">fiscal,</xsl:if>
                <xsl:if test="contains(@path,'uridique')=true()">juridique,</xsl:if>
                <xsl:if test="contains(@path,'social')=true()">social,</xsl:if>
                <xsl:if test="contains(@path,'multimedia')=true()">multimedia,</xsl:if>

            </xsl:for-each>
        </xsl:variable>

        <xsl:variable name="retour2">
            <xsl:value-of select="substring-before($retour,',')"/>
        </xsl:variable>

        <xsl:value-of select="$retour2"/>
    </xsl:template>

    <xsl:template match="refchapitre"/>

    <xsl:template match="p" mode="table">
        <xsl:apply-templates mode="table"/>
    </xsl:template>

    <xsl:template match="p">


        <xsl:variable name="t">
            <xsl:for-each select="*">
                <xsl:if test="name()='table' or name()='renvoistable' or @class='renvoistable'">ok</xsl:if>
            </xsl:for-each>
        </xsl:variable>

        <xsl:choose>
            <xsl:when test="contains($t,'ok')">
                <xsl:apply-templates/>
            </xsl:when>
            <xsl:otherwise>
                <texteparagraphe>
                    <xsl:apply-templates/>
                </texteparagraphe>
            </xsl:otherwise>
        </xsl:choose>

        <xsl:for-each select="*">
            <xsl:if test="name()='' and normalize-space(.)!=''">
                <texteparagraphe>
                    <xsl:value-of select="."/>
                </texteparagraphe>
            </xsl:if>
            <xsl:if test="name()='table'"> </xsl:if>

        </xsl:for-each>

    </xsl:template>


    <xsl:template match="p" mode="annotation">
        <xsl:apply-templates mode="annotation"/>
        <retourligne/>
    </xsl:template>

    <xsl:template match="p" mode="encadre">
        <retourligne/>
        <xsl:apply-templates mode="encadre"/>
    </xsl:template>

    <xsl:template match="BR"> </xsl:template>
    <xsl:template match="br">
        <retourligne/>
    </xsl:template>
    <xsl:template match="br" mode="table">
        <retourligne/>
    </xsl:template>

    <xsl:template match="BR" mode="annotation">
        <retourligne/>
    </xsl:template>
    <xsl:template match="br" mode="annotation">
        <retourligne/>
    </xsl:template>

    <xsl:template match="br" mode="encadre">
        <retourligne/>
    </xsl:template>

    <xsl:template match="li">
        <puce>&#149;</puce>&#160;<xsl:apply-templates/><retourligne/>
    </xsl:template>

    <xsl:template match="ul">

        <texteparagraphe>

            <xsl:apply-templates/>
        </texteparagraphe>

    </xsl:template>

    <xsl:template match="span">

        <xsl:variable name="test_cpl">
            <xsl:value-of select="normalize-space(.)"/>
        </xsl:variable>
        <xsl:choose>
            <xsl:when test="substring($test_cpl,1,2)='[['">
                <xsl:variable name="id_complement">
                    <xsl:value-of select="substring($test_cpl,3,string-length($test_cpl)-4)"/>
                </xsl:variable>

            </xsl:when>
            <xsl:when test="contains(@class,'renvoi')">
                <xsl:text disable-output-escaping="yes">&lt;</xsl:text><xsl:value-of
                    select="@class"
                    /><xsl:text disable-output-escaping="yes">&gt;</xsl:text><xsl:apply-templates
                    mode="nodiv"/>
                <xsl:text disable-output-escaping="yes">&lt;</xsl:text>/<xsl:value-of
                    select="@class"/><xsl:text disable-output-escaping="yes">&gt;</xsl:text>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates/>

            </xsl:otherwise>
        </xsl:choose>


    </xsl:template>

    <xsl:template match="span" mode="annotation">

        <xsl:if test="@class='texte_annotation'">
            <xsl:apply-templates mode="annotation"/>
        </xsl:if>
    </xsl:template>

    <xsl:template name="liens">
        <xsl:choose>
            <xsl:when test="contains(@href,'www.tpe-pme.com')">
                <xsl:value-of select="."/>
            </xsl:when>
            <xsl:otherwise>
                <lien>
                    <xsl:attribute name="href">
                        <xsl:value-of select="@href"/>
                    </xsl:attribute>
                    <xsl:value-of select="."/>
                </lien>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="a">
        <xsl:call-template name="liens"/>
    </xsl:template>

    <xsl:template match="a" mode="encadre">
        <xsl:call-template name="liens"/>
    </xsl:template>

    <xsl:template match="a" mode="annotation">
        <xsl:call-template name="liens"/>
    </xsl:template>


    <xsl:template match="em">
        <italique>
            <xsl:value-of select="."/>
        </italique>
    </xsl:template>
    <xsl:template match="em" mode="encadre">
        <italique>
            <xsl:value-of select="."/>
        </italique>
    </xsl:template>
    <xsl:template match="em" mode="annotation">
        <italique>
            <xsl:value-of select="."/>
        </italique>
    </xsl:template>

    <xsl:template match="sup">
        <exposant>
            <xsl:value-of select="."/>
        </exposant>
    </xsl:template>

    <xsl:template match="sup" mode="table">
        <exposant>
            <xsl:value-of select="."/>
        </exposant>
    </xsl:template>

    <xsl:template match="sup" mode="encadre">
        <exposant>
            <xsl:value-of select="."/>
        </exposant>
    </xsl:template>
    <xsl:template match="sup" mode="annotation">
        <exposant>
            <xsl:value-of select="."/>
        </exposant>
    </xsl:template>

    <xsl:template match="u">
        <souligne>
            <xsl:value-of select="."/>
        </souligne>
    </xsl:template>

    <xsl:template match="u" mode="encadre">
        <souligne>
            <xsl:value-of select="."/>
        </souligne>
    </xsl:template>

    <xsl:template match="u" mode="annotation">
        <souligne>
            <xsl:value-of select="."/>
        </souligne>
    </xsl:template>


    <xsl:template match="strong">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>

    <xsl:template match="strong" mode="encadre">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>

    <xsl:template match="strong" mode="annotation">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>
    <xsl:template match="strong" mode="table">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>

    <xsl:template match="b">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>

    <xsl:template match="B">
        <gras>
            <xsl:value-of select="."/>
        </gras>
    </xsl:template>

    <xsl:template match="tbody"> </xsl:template>

    <xsl:template match="tbody" mode="table">
        <xsl:apply-templates mode="table"/>
    </xsl:template>

    <xsl:template match="table">
        <!--     <xsl:if test="./tbody = true()">
<table><xsl:copy-of select="./tbody/*" /></table>
    </xsl:if>
      <xsl:if test="./tbody = false()">
-->


        <!-- recherche renvoistable -->
        <xsl:text disable-output-escaping="yes">
&lt;/paragraphe&gt;
&lt;paragraphe&gt;
</xsl:text>


        <xsl:if test="./caption = true()">
            <titretable>
                <xsl:value-of select="./caption"/>
            </titretable>
        </xsl:if>

        <table>
            <xsl:apply-templates mode="table"/>
        </table>
        <xsl:for-each select="following-sibling::p">
            <xsl:if test="position()=1 and name()='p'">
                <xsl:for-each select="node()">
                    <xsl:if test="name()='renvoistable'">
                        <renvoistable>
                            <xsl:apply-templates mode="table"/>
                        </renvoistable>
                    </xsl:if>
                </xsl:for-each>
            </xsl:if>
        </xsl:for-each>


        <xsl:text disable-output-escaping="yes">
&lt;/paragraphe&gt;
&lt;paragraphe&gt;

</xsl:text>
        <!--   </xsl:if> -->
    </xsl:template>
    <xsl:template match="caption"> </xsl:template>

    <xsl:template match="caption" mode="table"> </xsl:template>

    <xsl:template match="tr" mode="table">
        <tr>
            <xsl:apply-templates mode="table"/>
        </tr>
    </xsl:template>

    <xsl:template match="td" mode="table">
        <td>
            <xsl:for-each select="@*">
                <xsl:if
                    test="name(.)='width' or name(.)='align' or name(.)='valign' or name(.)='colspan' or name(.)='rowspan' or (name(.)='class' and (string(.)='texte1' or string(.)='texte2' or string(.)='texte3'))">


                    <xsl:attribute name="{name(.)}">
                        <xsl:value-of select="."/>
                    </xsl:attribute>
                </xsl:if>
            </xsl:for-each>


            <xsl:apply-templates mode="table"/>
        </td>
    </xsl:template>

    <xsl:template match="@*|node()" mode="table">
        <xsl:copy>
            <xsl:apply-templates select="@*"/>
            <xsl:apply-templates mode="table"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template match="div">


        <xsl:choose>
            <xsl:when test="string(@class)='reference'"/>


            <xsl:when test="string(@class)='intertitre'">
                <xsl:text disable-output-escaping="yes">
&lt;/paragraphe&gt;
&lt;paragraphe&gt;
</xsl:text>

                <titreparagraphe>
                    <xsl:value-of select="."/>
                </titreparagraphe>
            </xsl:when>

            <xsl:when test="string(@class)='titre_annotation' or string(@class)='titre_encadre'"> </xsl:when>
            <xsl:when test="string(@class)='texte_annotation' or string(@class)='texte_encadre'"> </xsl:when>

            <xsl:when test="string(@class)='encadre'">
                <xsl:call-template name="encadre"/>
            </xsl:when>

            <xsl:when test="string(@class)='annotation'">
                <xsl:call-template name="annotation"/>
            </xsl:when>


            <xsl:when test="string(@class)='texteparagraphe' or string(@class)='reference'">
                <texteparagraphe>
                    <xsl:apply-templates/>
                </texteparagraphe>
            </xsl:when>

            <xsl:when test="string(@class)='titreparagraphe'">
                <xsl:text disable-output-escaping="yes">
&lt;/paragraphe&gt;
&lt;paragraphe&gt;
</xsl:text>

                <titreparagraphe>
                    <xsl:apply-templates/>
                </titreparagraphe>
            </xsl:when>

            <xsl:otherwise>
                <xsl:variable name="nodiv">
                    <xsl:for-each select="*">
                        <xsl:if test="name()='div'">ok</xsl:if>
                    </xsl:for-each>
                </xsl:variable>

                <xsl:choose>
                    <xsl:when test="contains($nodiv,'ok')">
                        <xsl:apply-templates/>
                    </xsl:when>
                    <xsl:otherwise>
                        <texteparagraphe>
                            <xsl:apply-templates/>
                        </texteparagraphe>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:otherwise>

        </xsl:choose>

    </xsl:template>


    <xsl:template name="annotation">
        <xsl:variable name="texte_annotation">
            <xsl:apply-templates mode="annotation"/>
        </xsl:variable>
        <texteparagraphe>
            <annotation>
                <xsl:for-each select="*">
                    <xsl:choose>
                        <xsl:when test="name(.)='p'">

                            <xsl:for-each select="*">
                                <xsl:choose>
                                    <xsl:when
                                        test="string-length(.) &gt; 2 and name()='div' and string(@class)='titre_annotation'">
                                        <titreannotation>
                                            <xsl:value-of select="."/>
                                            <xsl:if
                                                test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':'"
                                                > :</xsl:if>
                                        </titreannotation>
                                    </xsl:when>
                                    <xsl:when
                                        test="name()='span' and string-length(normalize-space(.)) &gt; 2 and string(@class)='titre_annotation'">
                                        <titreannotation>
                                            <xsl:value-of select="."/>
                                            <xsl:if
                                                test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':'"
                                                > :</xsl:if>
                                        </titreannotation>
                                    </xsl:when>
                                </xsl:choose>
                            </xsl:for-each>


                        </xsl:when>
                        <xsl:when
                            test="string-length(.) &gt; 2 and name()='div' and string(@class)='titre_annotation'">
                            <titreannotation>
                                <xsl:value-of select="."/>
                                <xsl:if
                                    test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':'"
                                    > :</xsl:if>
                            </titreannotation>
                        </xsl:when>
                        <xsl:when
                            test="name()='span' and string-length(normalize-space(.)) &gt; 2 and string(@class)='titre_annotation'">
                            <titreannotation>
                                <xsl:value-of select="."/>
                                <xsl:if
                                    test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':'"
                                    > :</xsl:if>
                            </titreannotation>
                        </xsl:when>
                    </xsl:choose>
                </xsl:for-each>
                <texteannotation>
                    <xsl:choose>
                        <xsl:when test="substring(normalize-space($texte_annotation),1,1)=':'">
                            <xsl:value-of
                                select="substring(normalize-space($texte_annotation),2,string-length(normalize-space($texte_annotation)))"
                            />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:copy-of select="$texte_annotation"/>
                        </xsl:otherwise>


                    </xsl:choose>
                </texteannotation>
            </annotation>
        </texteparagraphe>

    </xsl:template>

    <xsl:template name="encadre">
        <xsl:variable name="texte_annotation">
            <xsl:apply-templates mode="encadre"/>
        </xsl:variable>
        <texteparagraphe>
            <annotation>
                <xsl:for-each select="*">
                    <xsl:choose>
                        <xsl:when
                            test="name()='div' and string-length(normalize-space(.)) &gt; 2 and string(@class)='titre_encadre'">
                            <titreannotation>
                                <xsl:value-of select="."/>
                                <xsl:if
                                    test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':' "
                                    > :</xsl:if>
                            </titreannotation>
                        </xsl:when>
                        <xsl:when
                            test="name()='span' and string(@class)='titre_encadre' and string-length(normalize-space(.)) &gt; 2 ">
                            <titreannotation>
                                <xsl:value-of select="."/>
                                <xsl:if
                                    test="substring(normalize-space(.),-1)!=':' and substring(normalize-space($texte_annotation),1,1)=':' "
                                    > :</xsl:if>
                            </titreannotation>
                        </xsl:when>
                    </xsl:choose>
                </xsl:for-each>
                <texteannotation>
                    <xsl:apply-templates mode="encadre"/>
                </texteannotation>
            </annotation>
        </texteparagraphe>
    </xsl:template>



    <xsl:template match="div" mode="annotation">

        <xsl:choose>
            <xsl:when test="string(@class)='titre_annotation' or string(@class)='titre_encadre'"> </xsl:when>
            <xsl:when test="string(@class)='texte_annotation' or string(@class)='texte_encadre'">
                <xsl:apply-templates mode="encadre"/>
            </xsl:when>
        </xsl:choose>


    </xsl:template>


    <xsl:template name="dateArticle">
        <xsl:param name="date"/>
        <xsl:variable name="annee" select="substring($date,1,4)"/>
        <xsl:variable name="mois" select="substring($date,6,2)"/>
        <xsl:variable name="jour" select="substring($date,9,2)"/>
        <xsl:value-of select="$jour"/>/<xsl:value-of select="$mois"/>/<xsl:value-of select="$annee"
        />
    </xsl:template>

    <xsl:template match="div" mode="encadre">
        <xsl:choose>
            <xsl:when test="string(@class)='titre_annotation' or string(@class)='titre_encadre'"> </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates mode="annotation"/>
            </xsl:otherwise>
        </xsl:choose>

    </xsl:template>

    <xsl:template match="renvoistable">
        <renvoistable>
            <xsl:apply-templates/>
        </renvoistable>
    </xsl:template>

    <xsl:template match="span" mode="encadre">
        <xsl:apply-templates mode="annotation"/>

    </xsl:template>


    <xsl:template match="hr">
        <xsl:text disable-output-escaping="yes">
&lt;/paragraphe&gt;
&lt;paragraphe&gt;
</xsl:text>
    </xsl:template>


</xsl:stylesheet>
