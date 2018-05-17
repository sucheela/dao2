<?php
$this->assign('title', 'My Matches');
$this->assign('css', $this->Html->css('matches.css'));
$this->assign('script', $this->Html->script([
  'bootstrap-notify.min.js',
  'load-more.js',
  'profile.js']));
?>
<div class="matches">
  <h1>My Matches</h1>
  <h4>Here are your matches based on your birth date and time.</h4>
  
  <ul>
  <?php
foreach ($matches as $m){
  echo $this->element('matchbox', $m);
}
  ?>
  </ul>
  <div class="ajax-loader" style="display: none;"></div>
</div>

<div class="popup-box chat-popup">
  <div class="popup-head">
    <div class="popup-head-left">Name Here - very very long name indeed -
    longer than the space can accomodate</div>
    <div class="popup-head-right"><a href="#" class="close-popup">&#10005;</a></div>
  </div><!-- .popup-head -->
  <div class="popup-messages">
    <div class="yours">
      <p>How are you doing?</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="mine">
      <p>Fine</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="mine">
      <p>Thank you. How are you?</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="yours">
      <p>Would you like to get a coffee with me? I don't have
    money. You'll have to pay for my coffee. And some muffins and donuts. I like
        donuts. And then maybe some smoothies too</p>
      <div class="when">1 year ago</div>      
    </div>
    <div class="mine">
      <p>No. Thank you.</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="yours">
      <p>Come on.</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="yours">
      <p>Bitch</p>
      <div class="when">1 year ago</div>
    </div>
    <div class="system">The asshole user is offline.</div>
    <div class="system text-danger">Ooops! Something is wrong.</div>
  </div><!-- .popup-message -->
  <div class="popup-foot">
      <textarea placeholder="Type your message here. Hit Enter to send."></textarea>
  </div><!-- .popup-foot -->
</div><!-- .popup-box -->
