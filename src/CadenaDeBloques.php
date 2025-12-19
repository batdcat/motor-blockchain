<?php

namespace Batdcat\Blockchain;

use Batdcat\Blockchain\Bloque;

/**
 * Clase CadenaDeBloques
 * Gestiona la integridad de la cadena y la dificultad de minado.
 */
class CadenaDeBloques
{
    /** @var Bloque[] $cadena */
    private array $cadena;
    private int $dificultad;

    public function __construct(int $dificultad = 4)
    {
        $this->cadena = [$this->crearBloqueGenesis()];
        $this->dificultad = $dificultad;
    }

    private function crearBloqueGenesis(): Bloque
    {
        return new Bloque(0, "Bloque Génesis: Inicio de Batdcat", "0");
    }

    public function obtenerUltimoBloque(): Bloque
    {
        return end($this->cadena);
    }

    /**
     * Agrega un nuevo bloque a la cadena tras minarlo.
     */
    public function agregarBloque(mixed $datos): void
    {
        $bloquePrevio = $this->obtenerUltimoBloque();
        
        $nuevoBloque = new Bloque(
            $bloquePrevio->indice + 1,
            $datos,
            $bloquePrevio->hash
        );

        // Aquí ocurre la "magia" del costo computacional
        $nuevoBloque->minarBloque($this->dificultad);
        
        $this->cadena[] = $nuevoBloque;
    }

    /**
     * Verifica si la cadena ha sido manipulada.
     */
    public function esCadenaValida(): bool
    {
        for ($i = 1; $i < count($this->cadena); $i++) {
            $bloqueActual = $this->cadena[$i];
            $bloquePrevio = $this->cadena[$i - 1];

            // 1. Verificar si el hash actual es legítimo
            if ($bloqueActual->hash !== $bloqueActual->calcularHash()) {
                return false;
            }

            // 2. Verificar si el enlace al anterior es correcto
            if ($bloqueActual->hashPrevio !== $bloquePrevio->hash) {
                return false;
            }
        }
        return true;
    }

    public function obtenerCadenaCompleta(): array
    {
        return $this->cadena;
    }
}
