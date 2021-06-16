<?php

namespace App\Entity;

use App\Repository\DictionnaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DictionnaireRepository::class)
 * @ORM\Table(name="dictionary")
 */
class Dictionnaire
{
    const TYPE_CONTRACT = 'contract';
    const TYPE_LOCATION = 'location';
    const TYPE_LOCATION2 = 'location2';
    const TYPE_DIPLOMA = 'diploma';
    const TYPE_EXPERIENCE = 'experience';
    const TYPE_LANGUAGE = 'language';
    const TYPE_LEVEL = 'level';
    const TYPE_REGION = 'region';
    const TYPE_CONTRACT_FORMATION = 'contract formation';
    const TYPE_SOURCE = 'source';
    const TYPE_START = 'debut';
    const TYPE_BUDGET = 'budget';
    const TYPE_SECTEUR = 'secteur';
    const TYPE_DURATION = 'duration';
    const TYPE_REFUS = 'refus';
    const TYPE_METIER = 'metier';
    const TYPE_ENTITE = 'entite';
    const TYPE_SOL = 'couleur_sol';
    const TYPE_PERSONNAGE = 'personnage';
    const TYPE_COMPTOIRE = 'comptoir';
    const TYPE_MOBILIER = 'mobilier';
    const TYPE_PLANTE = 'plante';
    const TYPE_TRANSPORT = 'transport';
    const TYPE_ENTRETIEN = 'entretien';
    const TYPE_FORMATION = 'formation';
    const TYPE_FORMATION1 = 'formation1';
    const TYPE_FORMATION2 = 'formation2';
    const TYPE_FORMATION3 = 'formation3';
    const TYPE_FORMATION4 = 'formation4';
    const TYPE_FORMATION5 = 'formation5';

    use ResourceId;

    /**
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private string $type;

    /**
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private string $value = '';


    /**
     * @ORM\ManyToOne(targetEntity="Dictionnaire", inversedBy="children")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $file;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }



    /**
     * Get list of availability statuses
     *
     * @return array
     */
    public static function getAvailabilityStatusList(): array
    {
        return array(
            '0' => 'Accessible',
            '1' => 'No accessible',
            '2' => 'Accessible under conditions'
        );
    }

    /**
     * Get experience field value
     *
     * @param $value
     *
     * @return string
     */
    public static function getExperienceName($value): string
    {
        switch($value) {
            case 1:
                return 'Expérience non précisée';
            case 2:
                return "Moins d'1 an";
            case 3:
                return '1 à 2 ans';
            case 4:
                return '2 à 5 ans';
            case 5:
                return '5 à 10 ans';
            case 6:
                return '10 à 15 ans';
            case 7:
                return 'Plus de 15 ans';
            case 8:
                return 'Tout niveau d\'expérience';
            case 9:
                return 'Etudiant / En formation';
            default:
                return 'Non renseigné';
        }
    }

    /**
     * Get diploma field text
     *
     * @param $value
     *
     * @return string
     */
    public static function getDiplomaName($value): string
    {
        switch ($value) {
            case 1:
                return 'Pas de diplôme requis';
            case 2:
                return 'CAP / BEP';
            case 3:
                return 'BAC / BAC Pro';
            case 4:
                return 'Bac + 1';
            case 5:
                return 'Bac + 2';
            case 6:
                return 'Bac + 3';
            case 7:
                return 'Bac + 4';
            case 8:
                return 'Bac + 5 et plus';
            default:
                return 'Non renseigné';
        }
    }

    /**
     * Get contract name by value
     *
     * @param $value
     *
     * @return string
     */
    public static function getContractNameByValue($value): string
    {
        if (is_array($value)) {
            return '';
        }

        switch($value) {
            case 1:
                return 'CDI';
            case 2:
                return 'CDD';
            case 3:
                return 'Interim';
            case 4:
                return 'Stage';
            case 5:
                return "Contrat d'apprentissage";
            case 6:
                return 'Contrat de professionalisation';
            case 7:
                return 'Autre';
            case 8:
                return 'Emploi saisonnier';
            default:
                return '';
        }
    }

    /**
     * Get contract field value
     *
     * @param $value
     *
     * @return string
     */
    public static function getContractName($value): string
    {
        if (!is_array($value)) {
            $value = explode('-', $value);
        }

        $result = array();
        foreach ($value as $_value) {
            switch($_value) {
                case 1:
                    $result[] = 'CDI';
                case 2:
                    $result[] = 'CDD';
                case 3:
                    $result[] = 'Interim';
                case 4:
                    $result[] = 'Stage';
                case 5:
                    $result[] = "Contrat d'apprentissage";
                case 6:
                    $result[] = 'Contrat de professionalisation';
                case 7:
                    $result[] = 'Autre';
                case 8:
                    $result[] = 'Emploi saisonnier';
                default:
                    return '';
            }

            return join(',', $result);
        }

    }

    public function __toString()
    {
        return $this->value;
    }

    public static function getTypeList(): array
    {
        return array(
            Dictionnaire::TYPE_CONTRACT => 'contract',
            Dictionnaire::TYPE_LOCATION => 'location',
            Dictionnaire::TYPE_LOCATION2 => 'Location2',
            Dictionnaire::TYPE_DIPLOMA => 'diploma',
            Dictionnaire::TYPE_EXPERIENCE => 'experience',
            Dictionnaire::TYPE_LANGUAGE => 'language',
            Dictionnaire::TYPE_LEVEL => 'level',
            Dictionnaire::TYPE_REGION => 'region',
            Dictionnaire::TYPE_CONTRACT_FORMATION => 'contract formation',
            Dictionnaire::TYPE_SOURCE => 'source',
            Dictionnaire::TYPE_START => 'debut',
            Dictionnaire::TYPE_BUDGET => 'budget',
            Dictionnaire::TYPE_SECTEUR => 'secteur',
            Dictionnaire::TYPE_DURATOIN  => 'duration',
            Dictionnaire::TYPE_REFUS  => 'refus',
            Dictionnaire::TYPE_METIER  => 'Categorie Metier',
            Dictionnaire::TYPE_ENTITE  => 'Entité',
            Dictionnaire::TYPE_SOL  => 'Couleur du sol',
            Dictionnaire::TYPE_PERSONNAGE  => 'Personnage',
            Dictionnaire::TYPE_COMPTOIRE  => 'Comptoire d’accueil',
            Dictionnaire::TYPE_MOBILIER  => 'Mobilier',
            Dictionnaire::TYPE_PLANTE  => 'Plante',
            Dictionnaire::TYPE_TRANSPORT  => 'Transport',
            Dictionnaire::TYPE_ENTRETIEN  => 'Entretien',
            Dictionnaire::TYPE_FORMATION  => 'Formation0',
            Dictionnaire::TYPE_FORMATION1  => 'Formation1',
            Dictionnaire::TYPE_FORMATION2  => 'Formation2',
            Dictionnaire::TYPE_FORMATION3  => 'Formation3',
            Dictionnaire::TYPE_FORMATION4  => 'Formation4',
            Dictionnaire::TYPE_FORMATION5  => 'Formation5',
        );
    }
}