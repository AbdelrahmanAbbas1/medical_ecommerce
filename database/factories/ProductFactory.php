<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Medications',
            'Medical Equipment', 
            'Surgical Instruments',
            'Diagnostic Tools',
            'Personal Care',
            'First Aid',
            'Laboratory Supplies',
            'Rehabilitation'
        ];
        
        $medications = [
            'Paracetamol 500mg', 'Aspirin 100mg', 'Ibuprofen 400mg',
            'Amoxicillin 250mg', 'Metformin 500mg', 'Lisinopril 10mg',
            'Atorvastatin 20mg', 'Omeprazole 20mg', 'Metoprolol 50mg'
        ];
        
        $equipment = [
            'Digital Blood Pressure Monitor', 'Stethoscope', 'Thermometer',
            'Pulse Oximeter', 'Blood Glucose Meter', 'Nebulizer',
            'Oxygen Concentrator', 'Wheelchair', 'Hospital Bed'
        ];
        
        $instruments = [
            'Surgical Scissors', 'Forceps', 'Scalpel Set',
            'Suture Kit', 'Syringe Set', 'Needle Holder',
            'Retractor', 'Hemostat', 'Surgical Gloves'
        ];
        
        $category = fake()->randomElement($categories);
        $name = '';
        
        switch($category) {
            case 'Medications':
                $name = fake()->randomElement($medications);
                break;
            case 'Medical Equipment':
                $name = fake()->randomElement($equipment);
                break;
            case 'Surgical Instruments':
                $name = fake()->randomElement($instruments);
                break;
            default:
                $name = fake()->words(2, true);
        }
        
        return [
            'name' => $name,
            'description' => fake()->paragraph(2),
            'price' => fake()->randomFloat(2, 2, 500),
            'stock' => fake()->numberBetween(0, 150),
            'category' => $category,
            'image' => null,
        ];
    }
}
