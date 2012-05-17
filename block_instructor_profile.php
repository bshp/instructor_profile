<?php

// Written at Louisiana State University

class block_instructor_profile extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_instructor_profile');
    }

    function applicable_formats() {
        return array('site' => false, 'my' => false, 'course' => true);
    }

    function get_content() {
        global $COURSE, $DB, $OUTPUT, $USER;

        $_s = function($key) { return get_string($key, 'block_instructor_profile'); };

        if ($this->content !== NULL) {
            return $this->content;
        }

        $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);

        $this->content = new stdClass;

        $params = array('courseid' => $COURSE->id);

        if (has_capability('moodle/course:update', $context)) {
            $url = new moodle_url('/blocks/instructor_profile/edit.php', $params);
            $link = html_writer::link($url, $_s('edit'));
        } else {
            $link = '';
        }

        $user = reset(get_users_by_capability($context, 'moodle/course:update'));

        if (!$profile = $DB->get_record('block_instructor_profile', $params)) {
            $this->content->text = $link;

            return $this->content;
        }

        $params = array(
            'courseid' => $COURSE->id,
            'size' => 100,
            'link' => false,
        );

        $out  = $OUTPUT->user_picture($user, $params);
        $out .= html_writer::tag('p', $profile->name);
        $out .= html_writer::tag('p', $profile->email);
        $out .= html_writer::tag('p', $profile->phone);
        $out .= html_writer::tag('p', implode('<br />', explode("\n", $profile->other)));

        $this->content->text = $out . $link;

        return $this->content;
    }
}