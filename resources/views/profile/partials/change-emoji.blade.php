
    <style>
        .emoji-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .emoji-item {
            width: 40px;
            height: 40px;
            cursor: pointer;
            border-radius: 50%;
            object-fit: cover;
        }

        /* New class to highlight selected emoji */
        .selected-emoji {
            border: 3px solid #007bff;
            background-color: #cce5ff;
            border-radius: 50%;
        }

    </style>

    <div class="container">
        <!-- Emoji Grid (Scrollable) -->
        <h5 class="pb-3">{{ __('choose_emoji')}}</h5>
        <div id="emoji-grid" class="emoji-grid">
            <!-- Dynamic emoji grid will be populated here -->
        </div>

        <!-- Save Button -->
        <div class="mt-4">
            <button class="btn btn-primary" id="save-btn">Save</button>
        </div>
    </div>

    <script>
        let selectedEmojiId = null; 
        let selectedEmojiUrl = null;
        let previousSelectedElement = null; 

        // Change the selected emoji and highlight it
        function selectEmoji(emojiUrl, emojiId, emojiElement) {
            selectedEmojiId = emojiId;
            selectedEmojiUrl = emojiUrl; 

            // If another emoji was previously selected, remove its highlight
            if (previousSelectedElement) {
                previousSelectedElement.classList.remove('selected-emoji');
            }
            emojiElement.classList.add('selected-emoji');
            previousSelectedElement = emojiElement;
        }

        // Function to dynamically fetch and display emojis
        function fetchEmojis() {
            fetch('{{ route('get.emojis') }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json(); 
                })
                .then(data => {
                    const emojiGrid = document.getElementById('emoji-grid');
                    emojiGrid.innerHTML = ''; 
                    data.forEach(emoji => {
                        // Create a new image element for each emoji
                        const imgElement = document.createElement('img');
                        imgElement.src = emoji.image_url; 
                        imgElement.classList.add('emoji-item'); 
                        imgElement.alt = "Emoji";
                        
                        // Set onclick event to select emoji and pass the img element
                        imgElement.onclick = () => selectEmoji(emoji.image_url, emoji.id, imgElement);

                        emojiGrid.appendChild(imgElement); 
                    });
                })
                .catch(error => console.error('Error fetching emojis:', error));
        }

        function saveSelection(event) {
            event.preventDefault(); // Prevent form submission or page reload

            if (!selectedEmojiId) {
                alert('Please select an emoji before saving.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Send the selected emoji ID to the backend
            fetch('{{ route('user.update.emoji') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Fetch the CSRF token dynamically
                },
                body: JSON.stringify({
                    emoji_id: selectedEmojiId 
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to save emoji.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Change the profile image to the selected emoji
                    const profileImage = document.getElementById('profile-image');
                    profileImage.src = selectedEmojiUrl;

                } else {
                    alert('Failed to save emoji.');
                }
            })
            .catch(error => console.error('Error saving emoji:', error));
        }

        // Fetch emojis on page load
        document.addEventListener('DOMContentLoaded', fetchEmojis);

        // Attach save event to button
        document.getElementById('save-btn').addEventListener('click', saveSelection);
    </script>



