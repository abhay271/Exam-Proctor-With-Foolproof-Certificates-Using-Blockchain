# Text-to-Speech (TTS) Feature Documentation

## Overview
The ExamFlow platform now includes an accessibility feature that allows students to listen to exam questions using Azure OpenAI's Text-to-Speech API.

## Features
- **Audio Playback**: Students can click a speaker icon next to any question to hear it read aloud
- **Voice Options**: Uses natural-sounding AI voices (default: "alloy")
- **Audio Caching**: Generated audio files are cached to reduce API calls and improve performance
- **Pause/Resume**: Click the button again to pause playback
- **Visual Feedback**: Button shows loading, playing, and ready states

## Technical Implementation

### Components

1. **`text_to_speech.php`**: Backend API handler
   - Accepts POST requests with question text
   - Calls Azure OpenAI TTS API
   - Caches audio files in `audio_cache/` directory
   - Returns audio URL to frontend

2. **`examportal.php`**: Updated exam interface
   - Speaker button added next to each question
   - JavaScript functions handle audio playback
   - Audio caching prevents repeated API calls

3. **`test_tts.php`**: Standalone test script
   - Verify TTS API connectivity
   - Test audio generation
   - Debug API configuration

### API Endpoint
```
https://abhay-mh5stdgf-swedencentral.cognitiveservices.azure.com/openai/deployments/tts/audio/speech?api-version=2025-03-01-preview
```

### Request Format
```json
{
  "model": "tts",
  "input": "Question text to convert to speech",
  "voice": "alloy"
}
```

### Available Voices
- `alloy` - Neutral, balanced (default)
- `echo` - Clear, professional
- `fable` - Warm, storytelling
- `onyx` - Deep, authoritative
- `nova` - Energetic, friendly
- `shimmer` - Soft, gentle

## Installation & Setup

### 1. Environment Configuration
The `.env` file already contains the required `AZURE_OPENAI_API_KEY`. The TTS feature uses the same API key.

### 2. Test the Setup
Navigate to: `http://localhost/Exam-Proctor-With-Foolproof-Certificates-Using-Blockchain/test_tts.php`

This will:
- Verify API connectivity
- Generate a test audio file
- Allow you to play and download the audio

### 3. Directory Permissions
Ensure the `students/audio_cache/` directory is writable:
```bash
chmod 755 students/audio_cache/
```

## Usage

### For Students
1. Open any exam in the exam portal
2. Look for the speaker icon (🔊) next to each question
3. Click the icon to hear the question read aloud
4. Click again to pause/stop playback
5. Audio files are cached for faster subsequent access

### For Developers

#### Changing the Voice
Edit `text_to_speech.php` line 48:
```php
$voice = isset($input_data['voice']) ? $input_data['voice'] : 'nova'; // Change default voice
```

Or modify the JavaScript call in `examportal.php`:
```javascript
voice: 'nova', // Change from 'alloy' to any valid voice
```

#### Adjusting Cache Behavior
Audio files are cached based on question ID and text hash. To clear cache:
```bash
rm students/audio_cache/*.mp3
```

#### Error Handling
Check PHP error logs if TTS fails:
```bash
tail -f /path/to/php_error.log
```

## API Usage & Costs

### Rate Limits
- Azure OpenAI TTS has rate limits based on your subscription tier
- Caching minimizes repeated API calls for the same question

### Cost Optimization
- Audio is cached per question to avoid repeated generation
- Cache files persist across exam sessions
- Consider implementing cache expiration if storage becomes an issue

## Troubleshooting

### Issue: "Failed to generate audio"
**Cause**: API key invalid or endpoint unreachable
**Solution**: 
1. Verify API key in `.env` file
2. Run `test_tts.php` to diagnose the issue
3. Check network connectivity to Azure

### Issue: Audio not playing
**Cause**: Browser doesn't support MP3 format or file path incorrect
**Solution**:
1. Check browser console for errors
2. Verify `audio_cache/` directory is accessible
3. Ensure .htaccess allows audio file access

### Issue: "HTTP 429 - Too Many Requests"
**Cause**: Rate limit exceeded
**Solution**:
1. Audio should be cached, check if caching is working
2. Implement request throttling
3. Contact Azure support to increase quota

### Issue: Audio quality issues
**Cause**: Text formatting or special characters
**Solution**:
1. Ensure question text is properly formatted
2. Remove HTML tags or special characters
3. Try a different voice option

## Security Considerations

1. **API Key Protection**: API key is stored in `.env` file (not in version control)
2. **Input Validation**: Text input is validated and sanitized before API call
3. **Rate Limiting**: Consider implementing rate limits per student
4. **Cache Management**: Implement periodic cache cleanup to manage storage

## Future Enhancements

### Planned Features
- [ ] Voice selection dropdown for students
- [ ] Speed control (slower/faster playback)
- [ ] Option to read all options (not just question)
- [ ] Download audio for offline access
- [ ] Multi-language support
- [ ] Text highlighting during speech

### SDG 4 Alignment
This feature supports **SDG 4: Quality Education** by:
- **Accessibility**: Helping visually impaired students
- **Learning Disabilities**: Supporting dyslexic and auditory learners
- **Multilingual Support**: Can be extended to support multiple languages
- **Inclusive Education**: Ensuring all students can access exam content

## API Documentation

### POST /students/text_to_speech.php

**Request Body**:
```json
{
  "text": "Question text to convert",
  "voice": "alloy",
  "question_id": "1"
}
```

**Success Response** (200):
```json
{
  "success": true,
  "audio_url": "audio_cache/question_1_abc123.mp3",
  "filename": "question_1_abc123.mp3",
  "size": 45678
}
```

**Error Response** (400/500):
```json
{
  "error": "Error message description"
}
```

## Support
For issues or questions about the TTS feature:
1. Check the error logs
2. Run the test script: `test_tts.php`
3. Verify Azure OpenAI service status
4. Contact development team

---

**Last Updated**: October 25, 2025
**Version**: 1.0.0
**Feature**: Text-to-Speech for Exam Questions
