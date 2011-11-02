<?xml version="1.0" encoding="iso-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="iso-8859-1"/>

<xsl:template match="//Document">
<Publishing>
        <Operation>LiveUpdate</Operation>
        <Document>
                <Code><xsl:value-of select="//Element/Id"/></Code>
                <DocumentNature><xsl:call-template name="DocumentNature" /></DocumentNature>
                <DataType><xsl:value-of select="//Element/Profil"/></DataType>
                <Metadata>
                        <AdministrativeMetadata>
                                <Provider><xsl:value-of select="//Element/Source"/></Provider>
                                <Creator><xsl:value-of select="//Element/Source"/></Creator>
                                <Source><xsl:value-of select="//Element/Source"/></Source>
                        </AdministrativeMetadata>
                        <AnteriorityMetadata>
                                <Media><xsl:value-of select="//Element/Source"/></Media>
                              <xsl:call-template name="segment_theme" />

                                <PublicationDate>
    <xsl:call-template name="date_complete"><xsl:with-param name="date"><xsl:value-of select="//Element/DateCreation"/>
                  </xsl:with-param></xsl:call-template>              </PublicationDate>
                                <IssueNumber><xsl:value-of select="//Element/Metas/Meta[@Issue]"/></IssueNumber>
            </AnteriorityMetadata>

    <DescriptiveMetadata>
    <Authors>
        <Author><Name><xsl:value-of select="//Element/Signature"/></Name><EMail>redaction-entrepreneur@lesechos.fr</EMail></Author>
    </Authors>
        <Companies>
        <xsl:for-each select="//Filtres/Type">
        <xsl:if test="string(@code)='societes'">
        <xsl:for-each select="Filtre">
        <Company>
        <Name><xsl:value-of select="Libelle"/></Name>

    <CompanyList>
<CompanyListItem>    
    <SirenCode></SirenCode>
    <IsinCode></IsinCode>
    </CompanyListItem>
</CompanyList>
    </Company>
        </xsl:for-each> 
        </xsl:if>               
        </xsl:for-each>
        </Companies>
        <Geographies>
        <xsl:for-each select="//Filtres/Type">
        <xsl:if test="string(@code)='geographies'">
        <xsl:for-each select="Filtre">
        <Geography><xsl:value-of select="Libelle"/></Geography>
        </xsl:for-each>
        </xsl:if>       
        </xsl:for-each>
        </Geographies>

    
    <Keywords>
    <xsl:for-each select="//Filtres/Type">
    <xsl:if test="string(@code)='secteurs' or string(@code)='thematiques'">
    <xsl:for-each select="Filtre">
    <Keyword><xsl:value-of select="LibelleMajuscules"/></Keyword>
    </xsl:for-each>
    </xsl:if>
    </xsl:for-each>
    </Keywords>

    <Language>FR</Language>
    <Abstract></Abstract>
    </DescriptiveMetadata>

            <ManagementMetadata>            
<Source><xsl:value-of select="//Element/Source"/></Source>                
                              <xsl:call-template name="segment_theme" />
<Subject></Subject>
<CreationDate><xsl:value-of select="//Element/CreatedAt"/></CreationDate>
<UpdateDate><xsl:value-of select="//Element/UpdatedAt"/></UpdateDate>
<VersionDate><xsl:value-of select="//Element/DateDernierePublication"/></VersionDate>
<VersionTime></VersionTime>
<PublicationDate>
<xsl:call-template name="date_complete"><xsl:with-param name="date"><xsl:value-of select="//Element/DateCreation"/></xsl:with-param></xsl:call-template></PublicationDate>

    <RelegationDate><xsl:value-of select="//Element/FinPublication"/></RelegationDate>
    <UnPublicationDate><xsl:value-of select="//Element/FinPublication"/></UnPublicationDate>
    <DeletionDate><xsl:value-of select="//Element/FinPublication"/></DeletionDate>
    <PriorityLevel></PriorityLevel>
    <WordCount><xsl:value-of select="//Element/NbMots"/></WordCount>
    <LineCount></LineCount>
    <SignCount></SignCount>
    <ForeseenCount></ForeseenCount>
</ManagementMetadata>

<RightsMetadata>
    <Copyright>
        <CopyrightHolder>Groupe Les Echos</CopyrightHolder>
        <CopyrightDate><xsl:value-of select="substring(//DateJour,0,5)" /></CopyrightDate>
    </Copyright>
    <UsageRights>
            <UsageRight>
                    <Recipient></Recipient>
            <UsageType></UsageType>
            <Geography>France</Geography>
            <RightsHolder></RightsHolder>
            <StartDate></StartDate>
            <EndDate></EndDate>

            <Limitations>Touts droits r&#233;serv&#233;s <xsl:value-of select="substring(//DateJour,0,5)" /> SID Presse - Groupe Les Echos -
Toutes les informations reproduites dans ce fichier sont
prot&#233;g&#233;es par les droits de propri&#233;t&#233;
intellectuelle d&#233;tenus par SID Presse. Aucune de ces informations
ne peut &#234;tre reproduite, modifi&#233;e, exploit&#233;e ou
r&#233;utilis&#233;e de quelque mani&#232;re que ce soit sans
l'accord pr&#233;alable &#233;crit de SID
Presse</Limitations>
        </UsageRight>
    </UsageRights>
    <ConfidentialityLevel></ConfidentialityLevel>
</RightsMetadata>
<LinkMetadata>
    <AssociatedWith>
    <Usage>URLLink</Usage>
    <Reference>http://entrepreneur.lesechos.fr<xsl:value-of select="//Element/Url" /></Reference>
    </AssociatedWith>
</LinkMetadata>
</Metadata>


<Data>
            <Introduction>
<Headline><xsl:value-of select="//Element/Titre"/></Headline>
<Byline><xsl:value-of select="//Element/Surtitre"/></Byline>
<Heading>
                    <Head><xsl:value-of select="//Element/Chapeau"/></Head>
<Info1><xsl:if test="//Element/Profil='AGENDA'"><xsl:value-of select="//Element/DateAgenda"/></xsl:if></Info1>
                    <Info2></Info2>
                    <Info3></Info3>
                    <Info4></Info4>
                    <Info5></Info5>
                    <Info6></Info6>
                </Heading>
<Notes></Notes>
</Introduction>
<Sections>
<Section>
    <xsl:apply-templates select="//Element/Texte"/>
</Section>
<xsl:if test="string(//Element/Signature)!=''">
<Section><Signature><xsl:value-of select="//Element/Signature"/></Signature></Section>
</xsl:if>
<Section>

<MultimediaInserts>
<xsl:for-each select="//Medias/Media[Inclus='non']">
    <MultimediaInsert>
    <FileName><xsl:value-of select="Fichier" /></FileName>
    <FileType><xsl:value-of select="substring(Type,5,string-length(Type)-5)"/></FileType>
    <Caption><xsl:value-of select="Titre"/><xsl:if test="Titre!='' and Texte!=''"> - </xsl:if><xsl:value-of select="Texte" /></Caption>
        <Usage><xsl:value-of select="Label"/></Usage>

    <PixelWidth><xsl:call-template name="trouver_meta"><xsl:with-param name="code">largeur</xsl:with-param></xsl:call-template></PixelWidth>
    <PixelHeight><xsl:call-template name="trouver_meta"><xsl:with-param name="code">hauteur</xsl:with-param></xsl:call-template></PixelHeight>    
    </MultimediaInsert>

</xsl:for-each>

</MultimediaInserts>
<Files>
<xsl:for-each select="//Complements/Complement">

        <File>
        <FileName><xsl:value-of select="Fichier" /></FileName>
        <FileType><xsl:value-of select="substring(Type,5,string-length(Type)-5)"/></FileType>
    <Caption><xsl:value-of select="Titre"/><xsl:if test="Titre!='' and Texte!=''"> - </xsl:if><xsl:value-of select="Texte" /></Caption>
        </File>
</xsl:for-each>
</Files>
</Section>
</Sections>
</Data>

</Document>
</Publishing>

</xsl:template>

    <xsl:template name="trouver_meta">
    <xsl:param name="code" />
    <xsl:for-each select="Metas/Meta">
    <xsl:if test="@code=$code"><xsl:value-of select="."/></xsl:if>
    </xsl:for-each>

    </xsl:template>

<xsl:template name="segment_theme">

<xsl:for-each select="//Rubriques/Rubrique">
    <xsl:if test="position()=1">
    
    <xsl:choose>
    <xsl:when test="string(Niveau)=1">
    <Segment><xsl:value-of select="Titre"/></Segment>
    </xsl:when>

    <xsl:otherwise>

        <xsl:for-each select="Hierarchies/Hierarchie[Niveau=1]">
            <Segment><xsl:value-of select="Titre"/></Segment>
        </xsl:for-each>
        
        <xsl:for-each select="Hierarchies/Hierarchie[Niveau=2]">
            <Theme><xsl:value-of select="Titre"/></Theme>
        </xsl:for-each>

        <xsl:choose>
        <xsl:when test="string(Niveau)=3">
            <SousTheme><xsl:value-of select="Titre"/></SousTheme>
        
        </xsl:when>    
        <xsl:otherwise>
        <xsl:for-each select="Hierarchies/Hierarchie[Niveau=3]">
            <SousTheme><xsl:value-of select="Titre"/></SousTheme>
        </xsl:for-each>
        </xsl:otherwise>
        </xsl:choose>

    </xsl:otherwise>
    

    </xsl:choose>


    </xsl:if>

</xsl:for-each>


</xsl:template>


<xsl:template name="DocumentNature">
<xsl:choose><xsl:when test="string(//Element/Metas/Meta[@nature])"></xsl:when>
    <xsl:otherwise>TXT</xsl:otherwise>
</xsl:choose>

</xsl:template>

<xsl:template match="p">

    <xsl:variable name="t">
    <xsl:for-each select="*">
    <xsl:if test="name()='table'">ok</xsl:if>
    </xsl:for-each>
    </xsl:variable>

    <xsl:choose>
    <xsl:when test="contains($t,'ok')">

    <xsl:for-each select="*">
    
        <xsl:choose>
        <xsl:when test="name()='table' and position()=1">
        <xsl:call-template name="table" />
    <xsl:apply-templates mode="tableau" />            
        </xsl:when>

        <xsl:when test="name()='tr' or name()='td' or name()='caption' or name()='tbody'"></xsl:when>
        </xsl:choose>

    </xsl:for-each>
    
    <xsl:variable name="texte"><xsl:apply-templates mode="tableau"/></xsl:variable>

    <xsl:if test="string($texte)!=' '">
    <Text><xsl:copy-of select="$texte" /></Text>
    </xsl:if>

    </xsl:when>
    <xsl:otherwise>

    <Text><xsl:apply-templates /></Text>
    </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<xsl:template match="*" mode="tableau"><xsl:if test="name(.)='' and name()!='tr' and name()!='td' and name()!='caption'"><xsl:apply-templates /></xsl:if></xsl:template>

<xsl:template match="BR">
<BR/>
</xsl:template>

<xsl:template match="br">
<BR/>
</xsl:template>


<xsl:template match="//Element/Texte">

    <xsl:apply-templates />

</xsl:template>



    <xsl:template match="li">
    <Indent><xsl:apply-templates/></Indent>
    </xsl:template>

    <xsl:template match="span">

    <xsl:variable name="test_cpl"><xsl:value-of select="normalize-space(.)" /></xsl:variable>
    <xsl:choose>
    <xsl:when test="substring($test_cpl,1,2)='[['">
    
    <MultimediaInserts>
    <xsl:variable name="id_complement"><xsl:value-of select="substring($test_cpl,3,string-length($test_cpl)-4)" /></xsl:variable>
    <xsl:for-each select="//Medias/Media[IdComplement=$id_complement]">
    <MultimediaInsert>
    <FileName><xsl:value-of select="Fichier" /></FileName>
    <FileType><xsl:value-of select="substring(Type,5,string-length(Type)-5)"/></FileType>
        <Usage><xsl:value-of select="Label"/></Usage>
    <Caption><xsl:value-of select="Texte" /></Caption>
    <PixelWidth><xsl:call-template name="trouver_meta"><xsl:with-param name="code">largeur</xsl:with-param></xsl:call-template></PixelWidth>
    <PixelHeight><xsl:call-template name="trouver_meta"><xsl:with-param name="code">hauteur</xsl:with-param></xsl:call-template></PixelHeight>    

    </MultimediaInsert>
    </xsl:for-each>    

    </MultimediaInserts>


    </xsl:when>
    <xsl:otherwise>
    <xsl:apply-templates />
    
    </xsl:otherwise>
    </xsl:choose>

    </xsl:template>

    <xsl:template match="strong">
    <B><xsl:value-of select="."/></B>
    </xsl:template>

    <xsl:template match="div">

    <xsl:choose>
        <xsl:when test="string(@class)='encadre'">
    <xsl:call-template name="encadre" />
    </xsl:when>
        <xsl:when test="string(@class)='annotation'">
    <xsl:call-template name="annotation" />
    </xsl:when>

    <xsl:when test="string(@class)='texteparagraphe'">
    <Text><xsl:apply-templates /></Text>
    </xsl:when>
    <xsl:when test="string(@class)='titreparagraphe' or string(@class)='intertitre'">
    <SubTitle><xsl:apply-templates /></SubTitle>
    </xsl:when>
    <xsl:when test="string(@class)='reference'">
    <Text><I><xsl:value-of select="."/></I></Text>
    </xsl:when>
    <xsl:otherwise>
    

    <xsl:variable name="testp">
        <xsl:for-each select="*">
            <xsl:if test="name()='p'">nok</xsl:if>
            <xsl:if test="name()='div'">nok</xsl:if>
        </xsl:for-each>
    </xsl:variable>
    

        <xsl:choose>
        <xsl:when test="contains($testp,'nok')"><xsl:apply-templates /></xsl:when>
        <xsl:otherwise>
        <Text>
        <xsl:apply-templates />
        </Text>
        </xsl:otherwise>
        </xsl:choose>
    

    </xsl:otherwise>

    </xsl:choose>


    </xsl:template>


<xsl:template name="date_complete">
<xsl:param name="date" />
<xsl:variable name="annee" select="substring($date,1,4)"/>
<xsl:variable name="mois" select="substring($date,6,2)"/>
<xsl:variable name="jour" select="substring($date,9,2)"/>

    <ISO><xsl:value-of select="$date"/></ISO>
    <Date>    
    <ShortDate><xsl:value-of select="$jour"/>/<xsl:value-of select="$mois"/>/<xsl:value-of select="$annee"/></ShortDate>
    <LongDate><xsl:value-of select="$jour"/>&#160;<xsl:call-template name="nom-du-mois">
<xsl:with-param name="mois" select="$mois"/>
</xsl:call-template>&#160;<xsl:value-of select="$annee"/></LongDate>
    <DayOfWeek><xsl:call-template name="calcul-jour-de-la-semaine">
        <xsl:with-param name="annee" select="$annee"/>
        <xsl:with-param name="mois" select="$mois"/>
        <xsl:with-param name="jour" select="$jour"/>
</xsl:call-template></DayOfWeek>
    </Date>
     
<!-- affichage de l'heure -->
            <Time>
            <ShortTime><xsl:value-of select="substring($date,12,2)"/>H<xsl:value-of select="substring($date,15,2)" /></ShortTime>
            </Time>        

</xsl:template>

<xsl:template name="nom-du-mois">

<xsl:param name="mois"/>

<xsl:choose>
<xsl:when test="$mois = 1">Janvier</xsl:when>
<xsl:when test="$mois = 2">Fevrier</xsl:when>
<xsl:when test="$mois = 3">Mars</xsl:when>
<xsl:when test="$mois = 4">Avril</xsl:when>
<xsl:when test="$mois = 5">Mai</xsl:when>
<xsl:when test="$mois = 6">Juin</xsl:when>
<xsl:when test="$mois = 7">Juillet</xsl:when>
<xsl:when test="$mois = 8">Aout</xsl:when>
<xsl:when test="$mois = 9">Septembre</xsl:when>
<xsl:when test="$mois = 10">Octobre</xsl:when>
<xsl:when test="$mois = 11">Novembre</xsl:when>
<xsl:when test="$mois = 12">Decembre</xsl:when>

</xsl:choose>

</xsl:template>


<xsl:template name="nom-jour-de-la-semaine">
        <xsl:param name="jour-de-la-semaine"/>
        <xsl:choose>
                <xsl:when test="$jour-de-la-semaine = 0">Dimanche</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 1">Lundi</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 2">Mardi</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 3">Mercredi</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 4">Jeudi</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 5">Vendredi</xsl:when>
                <xsl:when test="$jour-de-la-semaine = 6">Samedi</xsl:when>
        </xsl:choose>
</xsl:template>


<xsl:template name="calcul-jour-de-la-semaine">
        <xsl:param name="annee"/>
        <xsl:param name="mois"/>
        <xsl:param name="jour"/>

        <xsl:variable name="a" select="floor((14 - $mois) div 12)"/>
        <xsl:variable name="y" select="$annee - $a"/>
        <xsl:variable name="m" select="$mois + 12 * $a -2"/>
        <xsl:variable name="j" select="($jour + $y + floor($y div 4) - floor($y div 100) + floor($y div 400) + floor((31 * $m) div 12)) mod 7"/>

        <xsl:call-template name="nom-jour-de-la-semaine">
        <xsl:with-param name="jour-de-la-semaine" select="$j"/>
        </xsl:call-template>

</xsl:template>

<xsl:template match="a">

<ExternalLink>
<LinkText><xsl:apply-templates /></LinkText>
<URL><xsl:value-of select="@href" /></URL>
</ExternalLink>

</xsl:template>


<xsl:template match="a" mode="encadre">
<ExternalLink>
<LinkText><xsl:apply-templates /></LinkText>
<URL><xsl:value-of select="@href" /></URL>
</ExternalLink>

</xsl:template>

<xsl:template name="annotation">
<Text>
<Annotation>
<xsl:for-each select="*">
<xsl:choose>
<xsl:when test="name()='div' and string(@class)='titre_annotation'">
<TitreAnnotation><xsl:value-of select="."/></TitreAnnotation>
</xsl:when>
<xsl:when test="name()='span' and string(@class)='titre_annotation'">
<TitreAnnotation><xsl:value-of select="."/></TitreAnnotation>
</xsl:when>
</xsl:choose>
</xsl:for-each>
<TexteAnnotation>
<xsl:apply-templates mode="encadre"/>
</TexteAnnotation>
</Annotation>
</Text>

</xsl:template>

<xsl:template name="encadre">
<Encadre>
<xsl:for-each select="*">
<xsl:choose>
<xsl:when test="name()='span' and string(@class)='titre_encadre'">
<EncadreTitre><xsl:value-of select="."/></EncadreTitre>
</xsl:when>

<xsl:when test="name()='div' and string(@class)='titre_encadre'">
<EncadreTitre><xsl:value-of select="."/></EncadreTitre>
</xsl:when>


<xsl:otherwise>

</xsl:otherwise>
</xsl:choose>

</xsl:for-each>
<EncadreTexte>
<xsl:apply-templates mode="encadre"/>
</EncadreTexte>
</Encadre>

<!--
<xsl:apply-templates mode="titre_encadre" /> 

<EncadreTexte>
<xsl:apply-templates mode="sans_titre_encadre" />
</EncadreTexte>
</Encadre>
-->
</xsl:template>

<xsl:template match="*" mode="titre_encadre">
<xsl:if test="string(@class)='titre_annotation' or string(@class)='titre_encadre'">
<EncadreTitre><xsl:value-of select="."/></EncadreTitre>
</xsl:if>
</xsl:template>

<xsl:template match="*" mode="sans_titre_encadre">
<xsl:if test="string(@class)!='titre_annotation' and string(@class)!='titre_encadre'">
<xsl:apply-templates mode="encadre" />
</xsl:if>
</xsl:template>


<xsl:template match="div" mode="encadre">

</xsl:template>

<xsl:template match="span" mode="encadre">
<xsl:if test="@class='texte_annotation'">
<xsl:apply-templates />
</xsl:if>
</xsl:template>

 <xsl:template match="tbody">
    </xsl:template>

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


 
    <xsl:if test="./caption = true()">
    <titretable><xsl:value-of select="./caption" /></titretable>
    </xsl:if>

    <table> 
    <xsl:apply-templates  mode="table"/>
    </table>
            <xsl:for-each select="following-sibling::p">
            <xsl:if test="position()=1 and name()='p'">
            <xsl:for-each select="node()">
            <xsl:if test="name()='renvoistable'">
            <renvoistable><xsl:apply-templates mode="table" /></renvoistable>
            </xsl:if>
            </xsl:for-each>
            </xsl:if>
            </xsl:for-each>


    <!--   </xsl:if> -->
    </xsl:template>
    <xsl:template match="caption">
    </xsl:template>

    <xsl:template match="caption" mode="table">
    </xsl:template>

    <xsl:template match="tr" mode="table">
    <tr><xsl:apply-templates mode="table"/></tr>
    </xsl:template>

    <xsl:template match="td" mode="table">    
    <td>
<xsl:for-each select="@*">
<xsl:if test="name(.)='width' or name(.)='align' or name(.)='valign' or name(.)='colspan' or name(.)='rowspan' or (name(.)='class' and (string(.)='texte1' or string(.)='texte2' or string(.)='texte3'))">
 

<xsl:attribute name="{name(.)}"> 
<xsl:value-of select="."/> 
</xsl:attribute> 
</xsl:if>
</xsl:for-each> 


<xsl:apply-templates mode="table"/></td>
    </xsl:template>

    <xsl:template match="@*|node()" mode="table">
    <xsl:copy>
    <xsl:apply-templates select="@*"/>
    <xsl:apply-templates mode="table" />
    </xsl:copy>
    </xsl:template>


<xsl:template match="sup">
<Sup><xsl:value-of select="."/></Sup>
</xsl:template>

<xsl:template match="sup" mode="tableau">
<Sup><xsl:value-of select="."/></Sup>
</xsl:template>


<xsl:template match="p" mode="encadre">
<xsl:apply-templates />
</xsl:template>

<xsl:template match="BR" mode="encadre">
<BR/>
</xsl:template>
<xsl:template match="br" mode="encadre">
<BR/>
</xsl:template>



</xsl:stylesheet> 