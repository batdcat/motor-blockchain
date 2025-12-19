<?php

namespace Batdcat\Blockchain;

/**
 * Clase Bloque
 * Representa un eslabón único en la cadena. Es inmutable una vez minado.
 */
class Bloque
{
    public int $indice;
    public string $marcaDeTiempo;
    public mixed $datos; // Puede ser un array de transacciones, un voto, texto, etc.
    public string $hashPrevio;
    public string $hash;
    public int $nonce;

    public function __construct(int $indice, mixed $datos, string $hashPrevio = '')
    {
        $this->indice = $indice;
        $this->marcaDeTiempo = date('Y-m-d H:i:s');
        $this->datos = $datos;
        $this->hashPrevio = $hashPrevio;
        $this->nonce = 0;
        $this->hash = $this->calcularHash();
    }

    /**
     * Genera la huella digital única del bloque basada en su contenido.
     */
    public function calcularHash(): string
    {
        // Serializamos los datos para asegurar que cualquier cambio altere el hash
        $contenido = $this->indice . 
                     $this->marcaDeTiempo . 
                     $this->hashPrevio . 
                     $this->nonce . 
                     json_encode($this->datos);

        return hash('sha256', $contenido);
    }

    /**
     * Realiza el trabajo de minería (Proof of Work) hasta encontrar un hash válido.
     * @param int $dificultad Cantidad de ceros iniciales requeridos.
     */
    public function minarBloque(int $dificultad): void
    {
        // Creamos una cadena de ceros según la dificultad (ej: "0000")
        $objetivo = str_repeat("0", $dificultad);

        // Intentamos calcular hashes cambiando el nonce hasta cumplir el objetivo
        while (substr($this->hash, 0, $dificultad) !== $objetivo) {
            $this->nonce++;
            $this->hash = $this->calcularHash();
        }
    }
}
