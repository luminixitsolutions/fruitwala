<?php

/**
 * Load active FAQ items for the public FAQ page.
 *
 * @param mysqli $conn
 * @return array<int, array{id: int, question: string, answer: string}>
 */
function fruitwala_get_faqs(mysqli $conn): array
{
    $items = [];
    $sql = 'SELECT id, question, answer FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC';
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'id' => (int) $row['id'],
                'question' => (string) $row['question'],
                'answer' => (string) $row['answer'],
            ];
        }
        mysqli_free_result($result);
    }
    if ($items !== []) {
        return $items;
    }
    return fruitwala_faqs_fallback();
}

/**
 * Default Q&A when the table is missing or empty (matches previous static FAQ page).
 *
 * @return array<int, array{id: int, question: string, answer: string}>
 */
function fruitwala_faqs_fallback(): array
{
    return [
        ['id' => 1, 'question' => 'Where do your fruits come from?', 'answer' => 'We source our fruits daily from trusted local farms and wholesale markets to ensure maximum freshness, quality, and taste in every fruit box.'],
        ['id' => 2, 'question' => 'Are the fruits cleaned and hygienically packed?', 'answer' => 'Yes, all fruits are carefully washed, sorted, and hygienically packed in clean, safe boxes before delivery to maintain health and safety standards.'],
        ['id' => 3, 'question' => 'Do you offer customized fruit baskets?', 'answer' => 'Absolutely! We create customized fruit baskets for birthdays, events, corporate gifts, and special occasions. You can choose fruits, box styles, and quantities.'],
        ['id' => 4, 'question' => 'What areas do you deliver to?', 'answer' => 'We currently deliver to selected local areas. Contact us on Instagram or WhatsApp to confirm delivery availability in your location.'],
        ['id' => 5, 'question' => 'How fresh are your fruit boxes?', 'answer' => 'Our fruit boxes are prepared fresh on the same day of delivery to ensure maximum taste, nutrition, and freshness.'],
        ['id' => 6, 'question' => 'Can I place bulk or corporate orders?', 'answer' => 'Yes, we accept bulk and corporate orders for offices, events, and gifting. Special packaging and pricing are available for large orders.'],
        ['id' => 7, 'question' => 'How do I place an order?', 'answer' => 'You can place an order directly through our Instagram page or by contacting us via WhatsApp. Soon, ordering will also be available directly on our website.'],
        ['id' => 8, 'question' => 'Do you deliver on the same day?', 'answer' => 'Same-day delivery is available for orders placed before our daily cut-off time. Please contact us early to confirm availability.'],
    ];
}
