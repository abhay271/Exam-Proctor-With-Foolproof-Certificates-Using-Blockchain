# ✅ TTS Feature Testing Checklist

## Pre-Testing Setup

- [ ] XAMPP/WAMP server is running
- [ ] Apache and MySQL services started
- [ ] Browser opened (Chrome, Firefox, or Edge recommended)
- [ ] .env file exists in project root
- [ ] AZURE_OPENAI_API_KEY present in .env

---

## 🧪 Phase 1: API Connectivity Test

### Test the Azure OpenAI TTS API

1. [ ] Navigate to: `http://localhost/Exam-Proctor-With-Foolproof-Certificates-Using-Blockchain/test_tts.php`

2. [ ] **Expected Results:**
   - [ ] Page loads successfully
   - [ ] Shows "✓ SUCCESS! Audio generated successfully"
   - [ ] Displays file size (should be ~40-60 KB)
   - [ ] Audio player appears at bottom
   - [ ] Can play audio (hear test sentence)
   - [ ] Can download audio file

3. [ ] **If Failed:**
   - [ ] Check error message displayed
   - [ ] Verify API key in .env file
   - [ ] Check PHP error log
   - [ ] Ensure internet connection
   - [ ] Verify Azure endpoint is correct

---

## 🎨 Phase 2: Demo Page Test

### Test the interactive demo

1. [ ] Navigate to: `http://localhost/Exam-Proctor-With-Foolproof-Certificates-Using-Blockchain/tts_demo.html`

2. [ ] **Visual Check:**
   - [ ] Page displays with purple gradient background
   - [ ] Three questions visible
   - [ ] Each question has a speaker button (🔊)
   - [ ] Voice selector dropdown visible

3. [ ] **Question 1 Test:**
   - [ ] Click speaker button next to Q1
   - [ ] Button shows loading spinner
   - [ ] Status message: "Generating audio..."
   - [ ] Button changes to pause icon (green)
   - [ ] Audio plays (hear question)
   - [ ] Status message: "Audio generated successfully!"

4. [ ] **Question 2 Test:**
   - [ ] Click speaker button next to Q2
   - [ ] Audio plays (different question)
   - [ ] Works independently of Q1

5. [ ] **Question 3 Test:**
   - [ ] Click speaker button next to Q3
   - [ ] Audio plays successfully

6. [ ] **Voice Change Test:**
   - [ ] Select different voice from dropdown (e.g., "Nova")
   - [ ] Status: "Voice changed..."
   - [ ] Click Q1 speaker again
   - [ ] Audio regenerates with new voice
   - [ ] Can hear the voice difference

7. [ ] **Pause Test:**
   - [ ] Click speaker button to play Q1
   - [ ] While playing, click button again
   - [ ] Audio stops/pauses
   - [ ] Button returns to speaker icon

8. [ ] **Cache Test:**
   - [ ] Play Q1 audio
   - [ ] Wait for completion
   - [ ] Click Q1 speaker again
   - [ ] Status: "Playing cached audio"
   - [ ] Audio plays instantly (no loading)

---

## 🎓 Phase 3: Real Exam Test

### Test in actual exam environment

### 3.1 Login as Teacher

1. [ ] Navigate to teacher login
2. [ ] Login with teacher credentials
3. [ ] Go to "Exams" tab
4. [ ] Verify at least one exam exists
5. [ ] Note the exam name: _______________

### 3.2 Login as Student

1. [ ] Open new browser window/incognito
2. [ ] Navigate to student login
3. [ ] Login with student credentials
4. [ ] Go to "Exams" tab

### 3.3 Start Exam

1. [ ] Click "Start" on available exam
2. [ ] Wait for full-screen prompt
3. [ ] Click "Enter Full Screen"
4. [ ] Exam loads successfully

### 3.4 TTS Functionality Test

1. [ ] **Visual Check:**
   - [ ] Speaker button (🔊) visible next to question
   - [ ] Button is blue and looks clickable
   - [ ] Button positioned correctly

2. [ ] **First Play Test:**
   - [ ] Click speaker button
   - [ ] Button shows loading spinner
   - [ ] Takes 2-3 seconds
   - [ ] Button turns green with pause icon
   - [ ] Audio plays (hear question read aloud)
   - [ ] Audio quality is good (clear, natural)

3. [ ] **Navigation Test:**
   - [ ] Click "Next" to go to Q2
   - [ ] New question appears
   - [ ] Speaker button visible on Q2
   - [ ] Click Q2 speaker button
   - [ ] Audio plays for Q2

4. [ ] **Return Test:**
   - [ ] Click "Previous" to return to Q1
   - [ ] Q1 question visible
   - [ ] Click Q1 speaker button
   - [ ] Audio plays instantly (cached)
   - [ ] No loading delay

5. [ ] **Pause Test:**
   - [ ] Play Q1 audio
   - [ ] While playing, click button again
   - [ ] Audio stops
   - [ ] Button returns to speaker icon

6. [ ] **Multiple Questions Test:**
   - [ ] Test speaker button on at least 3 questions
   - [ ] All work correctly
   - [ ] No errors in browser console

7. [ ] **Browser Console Check:**
   - [ ] Press F12 to open console
   - [ ] No red error messages
   - [ ] May see info logs (okay)

---

## 📂 Phase 4: File System Check

### Verify audio files are being created

1. [ ] Navigate to: `students/audio_cache/` folder
2. [ ] **Expected:**
   - [ ] .htaccess file exists
   - [ ] .mp3 files present (after playing questions)
   - [ ] File names format: `question_X_HASH.mp3`

3. [ ] **File Size Check:**
   - [ ] Each MP3 is ~40-60 KB
   - [ ] Files are not empty (0 KB)

4. [ ] **Direct Access Test:**
   - [ ] Copy one MP3 filename
   - [ ] Try to access: `http://localhost/.../students/audio_cache/[filename].mp3`
   - [ ] Audio should download/play directly

---

## 🔒 Phase 5: Security Check

### Verify security measures

1. [ ] **API Key Protection:**
   - [ ] View source of examportal.php
   - [ ] API key is NOT visible in source
   - [ ] No sensitive data in JavaScript

2. [ ] **Direct Access Test:**
   - [ ] Try to access: `http://localhost/.../students/text_to_speech.php`
   - [ ] Without POST data, should return error
   - [ ] Not accessible via GET request

3. [ ] **Logout Test:**
   - [ ] Logout as student
   - [ ] Try to access exam page directly
   - [ ] Should redirect to login
   - [ ] TTS should not work without authentication

---

## 🌐 Phase 6: Browser Compatibility

### Test on multiple browsers

- [ ] **Chrome:**
  - [ ] Demo page works
  - [ ] Exam TTS works
  - [ ] Audio plays correctly

- [ ] **Firefox:**
  - [ ] Demo page works
  - [ ] Exam TTS works
  - [ ] Audio plays correctly

- [ ] **Edge:**
  - [ ] Demo page works
  - [ ] Exam TTS works
  - [ ] Audio plays correctly

- [ ] **Safari** (if available):
  - [ ] Demo page works
  - [ ] Exam TTS works
  - [ ] Audio plays correctly

---

## 📱 Phase 7: Mobile Testing (Optional)

### Test on mobile devices

- [ ] **Mobile Chrome:**
  - [ ] Can start exam
  - [ ] Speaker button visible
  - [ ] Audio plays on tap

- [ ] **Mobile Safari:**
  - [ ] Can start exam
  - [ ] Speaker button visible
  - [ ] Audio plays on tap

---

## ⚡ Phase 8: Performance Test

### Measure performance metrics

1. [ ] **First Load Test:**
   - [ ] Click speaker button
   - [ ] Time the loading: _____ seconds
   - [ ] Should be 2-3 seconds

2. [ ] **Cached Load Test:**
   - [ ] Click same button again
   - [ ] Time the loading: _____ seconds
   - [ ] Should be <1 second

3. [ ] **Multiple Questions:**
   - [ ] Play 5 different questions
   - [ ] All load successfully
   - [ ] No degradation in performance

---

## 🐛 Phase 9: Error Handling Test

### Test error scenarios

1. [ ] **Network Disconnection:**
   - [ ] Disconnect internet
   - [ ] Try to play new question
   - [ ] Should show error message
   - [ ] Error is user-friendly

2. [ ] **Invalid Input:**
   - [ ] (Technical test - optional)
   - [ ] Send malformed request to API
   - [ ] Should return error, not crash

---

## 📊 Phase 10: Final Verification

### Complete system check

- [ ] **Functionality:**
  - [ ] All speaker buttons work
  - [ ] Audio plays correctly
  - [ ] Caching works
  - [ ] No errors in exam flow

- [ ] **Performance:**
  - [ ] First load: <3 seconds
  - [ ] Cached load: <1 second
  - [ ] No lag or freezing

- [ ] **User Experience:**
  - [ ] Intuitive to use
  - [ ] Visual feedback clear
  - [ ] Audio quality excellent
  - [ ] No disruption to exam

- [ ] **Documentation:**
  - [ ] README files present
  - [ ] Test scripts work
  - [ ] Demo page functional

---

## ✅ Sign-Off

### Testing Completion

**Tested By:** _______________________

**Date:** _______________________

**Overall Result:**
- [ ] ✅ All tests passed
- [ ] ⚠️ Some issues (see notes below)
- [ ] ❌ Major issues (see notes below)

**Notes:**
```
[Write any issues, observations, or recommendations here]





```

**Recommendation:**
- [ ] ✅ Ready for production
- [ ] ⏳ Needs minor fixes
- [ ] ❌ Needs major revision

---

## 📋 Issue Tracking

### Issues Found (if any)

| Issue # | Description | Severity | Status |
|---------|-------------|----------|--------|
| 1 | | ⬜ Low ⬜ Medium ⬜ High | ⬜ Open ⬜ Fixed |
| 2 | | ⬜ Low ⬜ Medium ⬜ High | ⬜ Open ⬜ Fixed |
| 3 | | ⬜ Low ⬜ Medium ⬜ High | ⬜ Open ⬜ Fixed |

---

## 🎯 Success Criteria

### Feature is considered successful if:

- [x] ✅ API connectivity verified
- [x] ✅ Demo page functional
- [x] ✅ Exam integration working
- [x] ✅ Audio quality acceptable
- [x] ✅ Performance meets targets (<3s first, <1s cached)
- [x] ✅ No critical bugs
- [x] ✅ Browser compatibility confirmed
- [x] ✅ Security measures in place
- [x] ✅ Documentation complete

---

## 📞 Support

**If you encounter issues:**

1. Check `TTS_QUICK_START.md` for troubleshooting
2. Run `test_tts.php` to diagnose API issues
3. Review browser console for JavaScript errors
4. Check PHP error log for server-side issues
5. Verify .env file contains correct API key

**For help:**
- 📖 Documentation: TTS_FEATURE_README.md
- 🏗️ Architecture: TTS_ARCHITECTURE.md
- 📧 Contact: Development Team

---

**Checklist Version:** 1.0.0  
**Last Updated:** October 25, 2025  
**Status:** Ready for Testing ✅
