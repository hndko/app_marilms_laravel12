<?php

namespace App\Services;

class PromptBuilder
{
    /**
     * Build an optimized LLM prompt for generating structured quiz questions.
     *
     * @param array $params Configuration parameters: topic, category, difficulty, question_count, option_count, question_type, instructions.
     * @return string The formatted prompt string.
     */
    public function build(array $params): string
    {
        $topic = $params['topic'] ?? 'Pengetahuan Umum';
        $category = $params['category'] ?? 'Umum';
        $difficulty = $params['difficulty'] ?? 'sedang';
        $questionCount = (int) ($params['question_count'] ?? 5);
        $optionCount = (int) ($params['option_count'] ?? 4);
        $questionType = $params['question_type'] ?? 'multiple_choice';
        $additionalInstructions = trim($params['instructions'] ?? '');

        $prompt = <<<PROMPT
Anda adalah ahli pembuat soal evaluasi pendidikan profesional.
Buatlah paket kuis bertema "{$topic}" dalam kategori "{$category}" dengan spesifikasi berikut:

1. Jumlah Soal: Tepat {$questionCount} soal.
2. Tingkat Kesulitan: {$difficulty}.
3. Tipe Soal: {$questionType}.
4. Jumlah Opsi Jawaban per Soal: Tepat {$optionCount} opsi (hanya ada SATU opsi yang benar dengan "is_correct": true).
5. Bahasa: Bahasa Indonesia yang baku, jelas, akademis, dan mudah dipahami.
6. Penjelasan: Sertakan penjelasan ringkas ("explanation") pada setiap soal yang menjelaskan mengapa jawaban benar tersebut tepat.
PROMPT;

        if (!empty($additionalInstructions)) {
            $prompt .= "\n\nInstruksi Tambahan dari Pengajar:\n{$additionalInstructions}\n";
        }

        $prompt .= <<<SCHEMA

STRUKTUR OUTPUT JSON:
Anda HARUS mengembalikan SATU objek JSON tunggal dengan skema persis seperti di bawah ini tanpa teks prolog, epilog, atau formatting markdown code block:

{
  "title": "Judul Kuis yang Menarik (Maksimal 10 kata)",
  "description": "Deskripsi singkat mengenai apa yang diuji dalam kuis ini",
  "questions": [
    {
      "question_text": "Teks pertanyaan soal secara jelas...",
      "question_type": "multiple_choice",
      "points": 10,
      "explanation": "Penjelasan mengapa jawaban benar adalah yang terpilih",
      "options": [
        { "option_text": "Pilihan jawaban 1", "is_correct": true },
        { "option_text": "Pilihan jawaban 2", "is_correct": false },
        { "option_text": "Pilihan jawaban 3", "is_correct": false },
        { "option_text": "Pilihan jawaban 4", "is_correct": false }
      ]
    }
  ]
}

Pastikan tepat {$questionCount} soal dihasilkan dalam array "questions" dan setiap soal memiliki tepat {$optionCount} opsi jawaban dengan tepat satu "is_correct": true.
SCHEMA;

        return $prompt;
    }

    /**
     * Validate the parsed JSON structure to ensure all required keys and formatting are present.
     *
     * @param array $data The decoded JSON data.
     * @param int $expectedCount The expected number of questions.
     * @return bool True if valid, throws Exception if invalid.
     * @throws \Exception
     */
    public function validateStructure(array $data, int $expectedCount = 0): bool
    {
        if (!isset($data['title']) || !isset($data['questions']) || !is_array($data['questions'])) {
            throw new \Exception('Struktur JSON tidak valid: kurang key "title" atau "questions".');
        }

        if ($expectedCount > 0 && count($data['questions']) === 0) {
            throw new \Exception('LLM mengembalikan array "questions" kosong.');
        }

        foreach ($data['questions'] as $index => $q) {
            $num = $index + 1;
            if (empty($q['question_text'])) {
                throw new \Exception("Soal nomor {$num} tidak memiliki teks pertanyaan.");
            }

            if (!isset($q['options']) || !is_array($q['options']) || count($q['options']) < 2) {
                throw new \Exception("Soal nomor {$num} memiliki pilihan jawaban yang tidak valid atau kurang dari 2 opsi.");
            }

            $hasCorrect = false;
            foreach ($q['options'] as $opt) {
                if (empty($opt['option_text'])) {
                    throw new \Exception("Ada opsi jawaban kosong pada soal nomor {$num}.");
                }
                if (!empty($opt['is_correct']) && $opt['is_correct'] === true) {
                    $hasCorrect = true;
                }
            }

            if (!$hasCorrect) {
                throw new \Exception("Soal nomor {$num} tidak memiliki kunci jawaban benar (is_correct: true).");
            }
        }

        return true;
    }
}
