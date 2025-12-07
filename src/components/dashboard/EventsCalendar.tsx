import { useState, useMemo } from "react";
import { Calendar } from "@/components/ui/calendar";
import { ChevronRight, MoreHorizontal } from "lucide-react";
import { format } from "date-fns";

interface Exam {
  id: string;
  title: string;
  exam_date: string;
  exam_time?: string;
  course_name?: string;
}

interface EventsCalendarProps {
  exams?: Exam[];
}

export function EventsCalendar({ exams = [] }: EventsCalendarProps) {
  const [date, setDate] = useState<Date | undefined>(new Date());

  // Transform exams to events format
  const events = useMemo(() => {
    return exams.slice(0, 2).map((exam) => ({
      date: format(new Date(exam.exam_date), "dd MMM, yyyy"),
      title: exam.title || exam.course_name || "Exam",
      exam: exam,
    }));
  }, [exams]);

  return (
    <div className="chart-card animate-fade-in" style={{ animationDelay: "0.2s" }}>
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-foreground">Events Calendar</h3>
        <button className="p-1 hover:bg-muted rounded">
          <MoreHorizontal className="w-5 h-5 text-muted-foreground" />
        </button>
      </div>

      {/* Upcoming Events */}
      <div className="flex gap-4 mb-4">
        {events.length > 0 ? (
          events.map((event, index) => (
            <div key={index} className="flex-1 p-3 bg-muted rounded-lg">
              <p className="text-xs text-accent font-medium">{event.date}</p>
              <div className="flex items-center justify-between mt-1">
                <p className="text-sm font-medium text-foreground">{event.title}</p>
                <ChevronRight className="w-4 h-4 text-muted-foreground" />
              </div>
            </div>
          ))
        ) : (
          <div className="flex-1 p-3 bg-muted rounded-lg text-center">
            <p className="text-sm text-muted-foreground">No upcoming exams</p>
          </div>
        )}
      </div>

      {/* Calendar */}
      <Calendar
        mode="single"
        selected={date}
        onSelect={setDate}
        className="rounded-md w-full"
        classNames={{
          months: "flex flex-col sm:flex-row space-y-4 sm:space-x-4 sm:space-y-0",
          month: "space-y-4 w-full",
          caption: "flex justify-center pt-1 relative items-center",
          caption_label: "text-sm font-medium",
          nav: "space-x-1 flex items-center",
          nav_button: "h-7 w-7 bg-transparent p-0 opacity-50 hover:opacity-100",
          nav_button_previous: "absolute left-1",
          nav_button_next: "absolute right-1",
          table: "w-full border-collapse space-y-1",
          head_row: "flex w-full",
          head_cell: "text-muted-foreground rounded-md w-full font-normal text-[0.8rem]",
          row: "flex w-full mt-2",
          cell: "text-center text-sm p-0 relative w-full [&:has([aria-selected])]:bg-accent first:[&:has([aria-selected])]:rounded-l-md last:[&:has([aria-selected])]:rounded-r-md focus-within:relative focus-within:z-20",
          day: "h-8 w-8 p-0 font-normal aria-selected:opacity-100 hover:bg-muted rounded-full mx-auto",
          day_selected: "bg-accent text-accent-foreground hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground",
          day_today: "bg-primary text-primary-foreground",
          day_outside: "text-muted-foreground opacity-50",
          day_disabled: "text-muted-foreground opacity-50",
          day_range_middle: "aria-selected:bg-accent aria-selected:text-accent-foreground",
          day_hidden: "invisible",
        }}
      />
    </div>
  );
}
