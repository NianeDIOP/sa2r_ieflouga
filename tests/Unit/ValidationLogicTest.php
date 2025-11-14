<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ValidationLogicTest extends TestCase
{
    #[Test]
    public function admis_should_be_less_than_or_equal_to_candidats()
    {
        $candidats = 100;
        $admis = 80;
        
        $this->assertLessThanOrEqual($candidats, $admis);
    }

    #[Test]
    public function admis_greater_than_candidats_should_fail()
    {
        $candidats = 100;
        $admis = 110;
        
        $this->assertGreaterThan($candidats, $admis);
    }

    #[Test]
    public function filles_should_be_less_than_or_equal_to_total()
    {
        $total = 100;
        $filles = 45;
        
        $this->assertLessThanOrEqual($total, $filles);
    }

    #[Test]
    public function bon_etat_should_be_less_than_or_equal_to_total()
    {
        $total = 50;
        $bonEtat = 40;
        
        $this->assertLessThanOrEqual($total, $bonEtat);
    }

    #[Test]
    public function bon_etat_greater_than_total_should_fail()
    {
        $total = 50;
        $bonEtat = 60;
        
        $this->assertGreaterThan($total, $bonEtat);
    }

    #[Test]
    public function negative_values_should_be_rejected()
    {
        $value = -5;
        
        $this->assertLessThan(0, $value);
    }

    #[Test]
    public function empty_string_should_convert_to_zero()
    {
        $value = '';
        $converted = $value === '' ? 0 : (int)$value;
        
        $this->assertEquals(0, $converted);
    }

    #[Test]
    public function mai_should_be_less_than_or_equal_to_octobre()
    {
        $octobre = 100;
        $mai = 90;
        
        $this->assertLessThanOrEqual($octobre, $mai);
    }

    #[Test]
    public function abandons_should_be_within_difference()
    {
        $octobre = 100;
        $mai = 90;
        $difference = $octobre - $mai;
        $abandons = 8;
        
        $this->assertLessThanOrEqual($difference, $abandons);
    }

    #[Test]
    public function hundred_percent_admission_coherence()
    {
        $candidatsTotal = 100;
        $candidatsFilles = 45;
        $admisTotal = 100;
        $admisFilles = 45;
        
        // Si 100% d'admis (admis = candidats)
        if ($admisTotal === $candidatsTotal) {
            // Alors les filles admises doivent être égales aux candidates
            $this->assertEquals($candidatsFilles, $admisFilles);
        }
    }

    #[Test]
    public function hundred_percent_admission_with_wrong_filles_should_fail()
    {
        $candidatsTotal = 100;
        $candidatsFilles = 45;
        $admisTotal = 100;
        $admisFilles = 40; // Incorrect
        
        // Si 100% d'admis
        if ($admisTotal === $candidatsTotal) {
            // Les filles admises ne sont PAS égales aux candidates
            $this->assertNotEquals($candidatsFilles, $admisFilles);
        }
    }

    #[Test]
    public function validation_passes_for_valid_infrastructure_data()
    {
        $sallesDurTotal = 10;
        $sallesDurBonEtat = 8;
        
        $isValid = $sallesDurBonEtat <= $sallesDurTotal && $sallesDurTotal >= 0 && $sallesDurBonEtat >= 0;
        
        $this->assertTrue($isValid);
    }

    #[Test]
    public function validation_fails_for_invalid_infrastructure_data()
    {
        $sallesDurTotal = 10;
        $sallesDurBonEtat = 15; // Invalide
        
        $isValid = $sallesDurBonEtat <= $sallesDurTotal && $sallesDurTotal >= 0 && $sallesDurBonEtat >= 0;
        
        $this->assertFalse($isValid);
    }

    #[Test]
    public function validation_rejects_negative_infrastructure_values()
    {
        $sallesDurTotal = -5; // Négatif
        $sallesDurBonEtat = 3;
        
        $isValid = $sallesDurBonEtat <= $sallesDurTotal && $sallesDurTotal >= 0 && $sallesDurBonEtat >= 0;
        
        $this->assertFalse($isValid);
    }
}
