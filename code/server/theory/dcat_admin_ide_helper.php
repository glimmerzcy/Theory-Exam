<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection alias
     * @property Grid\Column|Collection authors
     * @property Grid\Column|Collection enable
     * @property Grid\Column|Collection imported
     * @property Grid\Column|Collection config
     * @property Grid\Column|Collection require
     * @property Grid\Column|Collection require_dev
     * @property Grid\Column|Collection college_code
     * @property Grid\Column|Collection twt_id
     * @property Grid\Column|Collection test_time
     * @property Grid\Column|Collection duration
     * @property Grid\Column|Collection started_at
     * @property Grid\Column|Collection ended_at
     * @property Grid\Column|Collection aim
     * @property Grid\Column|Collection related
     * @property Grid\Column|Collection tip
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection questions
     * @property Grid\Column|Collection content
     * @property Grid\Column|Collection published_at
     * @property Grid\Column|Collection is_exist
     * @property Grid\Column|Collection activated
     * @property Grid\Column|Collection stu_id
     * @property Grid\Column|Collection real_name
     * @property Grid\Column|Collection academic
     * @property Grid\Column|Collection profession
     * @property Grid\Column|Collection grade
     * @property Grid\Column|Collection gender
     * @property Grid\Column|Collection province
     * @property Grid\Column|Collection class
     * @property Grid\Column|Collection topic
     * @property Grid\Column|Collection objA
     * @property Grid\Column|Collection objB
     * @property Grid\Column|Collection objC
     * @property Grid\Column|Collection objD
     * @property Grid\Column|Collection objE
     * @property Grid\Column|Collection objF
     * @property Grid\Column|Collection answer
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection path
     * @property Grid\Column|Collection method
     * @property Grid\Column|Collection ip
     * @property Grid\Column|Collection input
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection paper_id
     * @property Grid\Column|Collection time
     * @property Grid\Column|Collection record
     * @property Grid\Column|Collection user_agent
     * @property Grid\Column|Collection score
     * @property Grid\Column|Collection stud_id
     * @property Grid\Column|Collection first_score
     * @property Grid\Column|Collection pass_score
     * @property Grid\Column|Collection exam_times
     * @property Grid\Column|Collection pass_time
     * @property Grid\Column|Collection verify_code
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection examid
     * @property Grid\Column|Collection major
     * @property Grid\Column|Collection school
     * @property Grid\Column|Collection stud_name
     * @property Grid\Column|Collection has_sub
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection tag
     * @property Grid\Column|Collection last_school
     * @property Grid\Column|Collection credit_per_question
     * @property Grid\Column|Collection draw_quantity
     * @property Grid\Column|Collection is_subjective
     * @property Grid\Column|Collection paper
     * @property Grid\Column|Collection email_verified_at
     *
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection alias(string $label = null)
     * @method Grid\Column|Collection authors(string $label = null)
     * @method Grid\Column|Collection enable(string $label = null)
     * @method Grid\Column|Collection imported(string $label = null)
     * @method Grid\Column|Collection config(string $label = null)
     * @method Grid\Column|Collection require(string $label = null)
     * @method Grid\Column|Collection require_dev(string $label = null)
     * @method Grid\Column|Collection college_code(string $label = null)
     * @method Grid\Column|Collection twt_id(string $label = null)
     * @method Grid\Column|Collection test_time(string $label = null)
     * @method Grid\Column|Collection duration(string $label = null)
     * @method Grid\Column|Collection started_at(string $label = null)
     * @method Grid\Column|Collection ended_at(string $label = null)
     * @method Grid\Column|Collection aim(string $label = null)
     * @method Grid\Column|Collection related(string $label = null)
     * @method Grid\Column|Collection tip(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection questions(string $label = null)
     * @method Grid\Column|Collection content(string $label = null)
     * @method Grid\Column|Collection published_at(string $label = null)
     * @method Grid\Column|Collection is_exist(string $label = null)
     * @method Grid\Column|Collection activated(string $label = null)
     * @method Grid\Column|Collection stu_id(string $label = null)
     * @method Grid\Column|Collection real_name(string $label = null)
     * @method Grid\Column|Collection academic(string $label = null)
     * @method Grid\Column|Collection profession(string $label = null)
     * @method Grid\Column|Collection grade(string $label = null)
     * @method Grid\Column|Collection gender(string $label = null)
     * @method Grid\Column|Collection province(string $label = null)
     * @method Grid\Column|Collection class(string $label = null)
     * @method Grid\Column|Collection topic(string $label = null)
     * @method Grid\Column|Collection objA(string $label = null)
     * @method Grid\Column|Collection objB(string $label = null)
     * @method Grid\Column|Collection objC(string $label = null)
     * @method Grid\Column|Collection objD(string $label = null)
     * @method Grid\Column|Collection objE(string $label = null)
     * @method Grid\Column|Collection objF(string $label = null)
     * @method Grid\Column|Collection answer(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection path(string $label = null)
     * @method Grid\Column|Collection method(string $label = null)
     * @method Grid\Column|Collection ip(string $label = null)
     * @method Grid\Column|Collection input(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection paper_id(string $label = null)
     * @method Grid\Column|Collection time(string $label = null)
     * @method Grid\Column|Collection record(string $label = null)
     * @method Grid\Column|Collection user_agent(string $label = null)
     * @method Grid\Column|Collection score(string $label = null)
     * @method Grid\Column|Collection stud_id(string $label = null)
     * @method Grid\Column|Collection first_score(string $label = null)
     * @method Grid\Column|Collection pass_score(string $label = null)
     * @method Grid\Column|Collection exam_times(string $label = null)
     * @method Grid\Column|Collection pass_time(string $label = null)
     * @method Grid\Column|Collection verify_code(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection examid(string $label = null)
     * @method Grid\Column|Collection major(string $label = null)
     * @method Grid\Column|Collection school(string $label = null)
     * @method Grid\Column|Collection stud_name(string $label = null)
     * @method Grid\Column|Collection has_sub(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection tag(string $label = null)
     * @method Grid\Column|Collection last_school(string $label = null)
     * @method Grid\Column|Collection credit_per_question(string $label = null)
     * @method Grid\Column|Collection draw_quantity(string $label = null)
     * @method Grid\Column|Collection is_subjective(string $label = null)
     * @method Grid\Column|Collection paper(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection name
     * @property Show\Field|Collection version
     * @property Show\Field|Collection alias
     * @property Show\Field|Collection authors
     * @property Show\Field|Collection enable
     * @property Show\Field|Collection imported
     * @property Show\Field|Collection config
     * @property Show\Field|Collection require
     * @property Show\Field|Collection require_dev
     * @property Show\Field|Collection college_code
     * @property Show\Field|Collection twt_id
     * @property Show\Field|Collection test_time
     * @property Show\Field|Collection duration
     * @property Show\Field|Collection started_at
     * @property Show\Field|Collection ended_at
     * @property Show\Field|Collection aim
     * @property Show\Field|Collection related
     * @property Show\Field|Collection tip
     * @property Show\Field|Collection status
     * @property Show\Field|Collection questions
     * @property Show\Field|Collection content
     * @property Show\Field|Collection published_at
     * @property Show\Field|Collection is_exist
     * @property Show\Field|Collection activated
     * @property Show\Field|Collection stu_id
     * @property Show\Field|Collection real_name
     * @property Show\Field|Collection academic
     * @property Show\Field|Collection profession
     * @property Show\Field|Collection grade
     * @property Show\Field|Collection gender
     * @property Show\Field|Collection province
     * @property Show\Field|Collection class
     * @property Show\Field|Collection topic
     * @property Show\Field|Collection objA
     * @property Show\Field|Collection objB
     * @property Show\Field|Collection objC
     * @property Show\Field|Collection objD
     * @property Show\Field|Collection objE
     * @property Show\Field|Collection objF
     * @property Show\Field|Collection answer
     * @property Show\Field|Collection id
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection order
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection path
     * @property Show\Field|Collection method
     * @property Show\Field|Collection ip
     * @property Show\Field|Collection input
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection username
     * @property Show\Field|Collection password
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection token
     * @property Show\Field|Collection paper_id
     * @property Show\Field|Collection time
     * @property Show\Field|Collection record
     * @property Show\Field|Collection user_agent
     * @property Show\Field|Collection score
     * @property Show\Field|Collection stud_id
     * @property Show\Field|Collection first_score
     * @property Show\Field|Collection pass_score
     * @property Show\Field|Collection exam_times
     * @property Show\Field|Collection pass_time
     * @property Show\Field|Collection verify_code
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection examid
     * @property Show\Field|Collection major
     * @property Show\Field|Collection school
     * @property Show\Field|Collection stud_name
     * @property Show\Field|Collection has_sub
     * @property Show\Field|Collection email
     * @property Show\Field|Collection tag
     * @property Show\Field|Collection last_school
     * @property Show\Field|Collection credit_per_question
     * @property Show\Field|Collection draw_quantity
     * @property Show\Field|Collection is_subjective
     * @property Show\Field|Collection paper
     * @property Show\Field|Collection email_verified_at
     *
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection alias(string $label = null)
     * @method Show\Field|Collection authors(string $label = null)
     * @method Show\Field|Collection enable(string $label = null)
     * @method Show\Field|Collection imported(string $label = null)
     * @method Show\Field|Collection config(string $label = null)
     * @method Show\Field|Collection require(string $label = null)
     * @method Show\Field|Collection require_dev(string $label = null)
     * @method Show\Field|Collection college_code(string $label = null)
     * @method Show\Field|Collection twt_id(string $label = null)
     * @method Show\Field|Collection test_time(string $label = null)
     * @method Show\Field|Collection duration(string $label = null)
     * @method Show\Field|Collection started_at(string $label = null)
     * @method Show\Field|Collection ended_at(string $label = null)
     * @method Show\Field|Collection aim(string $label = null)
     * @method Show\Field|Collection related(string $label = null)
     * @method Show\Field|Collection tip(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection questions(string $label = null)
     * @method Show\Field|Collection content(string $label = null)
     * @method Show\Field|Collection published_at(string $label = null)
     * @method Show\Field|Collection is_exist(string $label = null)
     * @method Show\Field|Collection activated(string $label = null)
     * @method Show\Field|Collection stu_id(string $label = null)
     * @method Show\Field|Collection real_name(string $label = null)
     * @method Show\Field|Collection academic(string $label = null)
     * @method Show\Field|Collection profession(string $label = null)
     * @method Show\Field|Collection grade(string $label = null)
     * @method Show\Field|Collection gender(string $label = null)
     * @method Show\Field|Collection province(string $label = null)
     * @method Show\Field|Collection class(string $label = null)
     * @method Show\Field|Collection topic(string $label = null)
     * @method Show\Field|Collection objA(string $label = null)
     * @method Show\Field|Collection objB(string $label = null)
     * @method Show\Field|Collection objC(string $label = null)
     * @method Show\Field|Collection objD(string $label = null)
     * @method Show\Field|Collection objE(string $label = null)
     * @method Show\Field|Collection objF(string $label = null)
     * @method Show\Field|Collection answer(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection path(string $label = null)
     * @method Show\Field|Collection method(string $label = null)
     * @method Show\Field|Collection ip(string $label = null)
     * @method Show\Field|Collection input(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection paper_id(string $label = null)
     * @method Show\Field|Collection time(string $label = null)
     * @method Show\Field|Collection record(string $label = null)
     * @method Show\Field|Collection user_agent(string $label = null)
     * @method Show\Field|Collection score(string $label = null)
     * @method Show\Field|Collection stud_id(string $label = null)
     * @method Show\Field|Collection first_score(string $label = null)
     * @method Show\Field|Collection pass_score(string $label = null)
     * @method Show\Field|Collection exam_times(string $label = null)
     * @method Show\Field|Collection pass_time(string $label = null)
     * @method Show\Field|Collection verify_code(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection examid(string $label = null)
     * @method Show\Field|Collection major(string $label = null)
     * @method Show\Field|Collection school(string $label = null)
     * @method Show\Field|Collection stud_name(string $label = null)
     * @method Show\Field|Collection has_sub(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection tag(string $label = null)
     * @method Show\Field|Collection last_school(string $label = null)
     * @method Show\Field|Collection credit_per_question(string $label = null)
     * @method Show\Field|Collection draw_quantity(string $label = null)
     * @method Show\Field|Collection is_subjective(string $label = null)
     * @method Show\Field|Collection paper(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     */
    class Show {}

    /**
     
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     
     */
    class Column {}

    /**
     
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     
     */
    class Field {}
}
