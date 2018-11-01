<?php

namespace NetBS\FichierBundle\Exporter;

use NetBS\CoreBundle\Exporter\PDFPreviewer;
use NetBS\CoreBundle\Model\ConfigurableExporterInterface;
use NetBS\CoreBundle\Model\ExporterInterface;
use NetBS\CoreBundle\Utils\Traits\ConfigurableExporterTrait;
use NetBS\FichierBundle\Exporter\Config\EtiquettesConfig;
use NetBS\FichierBundle\Exporter\Model\EtiquettesBuilder;
use NetBS\FichierBundle\Form\Export\EtiquettesType;
use NetBS\FichierBundle\Mapping\BaseFamille;
use NetBS\FichierBundle\Mapping\BaseMembre;
use NetBS\FichierBundle\Model\AdressableInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PDFEtiquettes implements ExporterInterface, ConfigurableExporterInterface
{
    use ConfigurableExporterTrait;

    /**
     * Returns an alias representing this exporter
     * @return string
     */
    public function getAlias()
    {
        return 'pdf.etiquettes';
    }

    /**
     * Returns the exported item's class
     * @return string
     */
    public function getExportableClass()
    {
        return AdressableInterface::class;
    }

    /**
     * Returns a displayable name of this exporter
     * @return string
     */
    public function getName()
    {
        return "Etiquettes";
    }

    /**
     * @param AdressableInterface[] $adressables
     * @return string
     */
    public function export($adressables)
    {
        /** @var EtiquettesConfig $config */
        $config = $this->configuration;
        $fpdf   = new EtiquettesBuilder($this->configuration);
        $infos  = [
            "Informations d'exportation",
            "Nombre d'éléments fournis: " . count($adressables),
        ];

        if($config->mergeFamilles) {
            $adressables = $this->mergeMembres($adressables);
            $infos[] = "Nombre d'éléments après fusion des familles: " . count($adressables);
        }

        $etiquettes = [];
        $noAddress  = 0;

        // Building etiquettes
        foreach ($adressables as $adressable) {

            $adresse    = $adressable->getSendableAdresse();
            $lines      = [];

            if(!empty($config->title) && $adressable instanceof BaseMembre)
                $lines[] = $config->title;

            $lines[]    = $adressable->__toString();

            if($adresse) {

                $lines[]    = $adresse->getRue();
                $lines[]    = $adresse->getNpa() . " " . $adresse->getLocalite();
            }
            else {
                $noAddress++;
            }

            if($adresse || $config->displayNotAdressable)
                $etiquettes[] = $lines;
        }

        $infos[] = "Nombre d'éléments sans adresses trouvés: " . $noAddress;

        //Print infos
        $fpdf->AddPage();
        for($i = 0; $i < count($infos); $i++) {
            $fpdf->SetXY(20, 20 + (10*$i));
            $fpdf->MultiCell(200, 6, utf8_decode($infos[$i]), 0, 'L');
        }

        //Output etiquettes
        $fpdf->AddPage();
        foreach($etiquettes as $etiquette)
            $fpdf->addEtiquette($etiquette);

        return  new StreamedResponse(function() use ($fpdf) {
            $fpdf->Output();
        });
    }

    private function mergeMembres($adressables) {

        $result = [];

        foreach($adressables as $adressable) {
            if($adressable instanceof BaseFamille)
                $result[$adressable->getId()] = $adressable;

            else {

                /** @var BaseMembre $adressable */
                $id = $adressable->getFamille()->getId();
                if(!isset($result[$id]))
                    $result[$id] = [];

                $result[$id][] = $adressable;
            }
        }

        return array_map(function($item) {
            if($item instanceof BaseFamille)
                return $item;
            elseif(count($item) > 1)
                return $item[0]->getFamille();
            elseif(count($item) === 1)
                return $item[0];
        }, $result);
    }

    /**
     * Returns this exported items file extension
     * @return string
     */
    public function getCategory()
    {
        return 'pdf';
    }

    /**
     * Retournes le formulaire de configuration de l'exportation
     * @return string|null
     */
    public function getConfigFormClass()
    {
        return EtiquettesType::class;
    }

    /**
     * Retournes la classe faisant office de configuration pour cette exportation,
     * Si retourne null, aucune configuration n'est chargée et le fichier est directement exporté
     * @return string|null
     */
    public function getConfigClass()
    {
        return EtiquettesConfig::class;
    }

    /**
     * If the rendered file can be previewed, return the used
     * previewer class
     * @return string
     */
    public function getPreviewer()
    {
        return PDFPreviewer::class;
    }
}