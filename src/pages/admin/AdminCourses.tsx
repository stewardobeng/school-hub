import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { BookOpen, Plus, Search, MoreVertical, Users, Clock, GraduationCap, Edit, Trash2, Eye, Calendar } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useToast } from "@/components/ui/use-toast";

const initialCourses = [
  {
    id: "CRS001",
    name: "Mathematics - Algebra",
    code: "MATH-101",
    grade: "Grade 10",
    teacher: "Dr. Robert Anderson",
    students: 30,
    credits: 3,
    duration: "90 min",
    status: "Active",
    schedule: "Mon, Wed, Fri - 9:00 AM",
  },
  {
    id: "CRS002",
    name: "Science - Physics",
    code: "SCI-201",
    grade: "Grade 11",
    teacher: "Prof. Maria Garcia",
    students: 25,
    credits: 4,
    duration: "90 min",
    status: "Active",
    schedule: "Tue, Thu - 10:30 AM",
  },
  {
    id: "CRS003",
    name: "English Literature",
    code: "ENG-101",
    grade: "Grade 9",
    teacher: "Ms. Jennifer Lee",
    students: 28,
    credits: 3,
    duration: "60 min",
    status: "Active",
    schedule: "Mon, Wed, Fri - 11:00 AM",
  },
  {
    id: "CRS004",
    name: "World History",
    code: "HIS-201",
    grade: "Grade 12",
    teacher: "Mr. James Taylor",
    students: 22,
    credits: 3,
    duration: "90 min",
    status: "Active",
    schedule: "Tue, Thu - 2:00 PM",
  },
  {
    id: "CRS005",
    name: "Computer Science",
    code: "CS-301",
    grade: "Grade 10",
    teacher: "Dr. Lisa Chen",
    students: 30,
    credits: 4,
    duration: "120 min",
    status: "Active",
    schedule: "Mon, Wed - 1:00 PM",
  },
  {
    id: "CRS006",
    name: "Physical Education",
    code: "PE-101",
    grade: "Grade 11",
    teacher: "Mr. Thomas White",
    students: 25,
    credits: 2,
    duration: "60 min",
    status: "Active",
    schedule: "Tue, Thu, Fri - 3:00 PM",
  },
];

// Mock data for course students
const getCourseStudents = (courseId: string) => {
  const students: Record<string, any[]> = {
    CRS001: [
      { id: "ST001", name: "John Smith", grade: "A", attendance: 95 },
      { id: "ST002", name: "Emily Johnson", grade: "A-", attendance: 98 },
      { id: "ST003", name: "Michael Brown", grade: "B+", attendance: 92 },
    ],
  };
  return students[courseId] || [];
};

export default function AdminCourses() {
  const navigate = useNavigate();
  const [courses, setCourses] = useState(initialCourses);
  const [searchQuery, setSearchQuery] = useState("");
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [isStudentsDialogOpen, setIsStudentsDialogOpen] = useState(false);
  const [selectedCourse, setSelectedCourse] = useState<any>(null);
  const [formData, setFormData] = useState({
    name: "",
    code: "",
    grade: "",
    teacher: "",
    credits: "",
    duration: "",
    schedule: "",
    status: "Active",
  });
  const { toast } = useToast();

  const totalCourses = 156;
  const activeCourses = 148;
  const totalEnrollments = 12478;

  const filteredCourses = courses.filter((course) =>
    course.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    course.code.toLowerCase().includes(searchQuery.toLowerCase()) ||
    course.teacher.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newCourse = {
      id: `CRS${String(courses.length + 1).padStart(3, '0')}`,
      ...formData,
      credits: parseInt(formData.credits),
      students: 0,
    };
    setCourses([...courses, newCourse]);
    toast({
      title: "Course Created",
      description: `${formData.name} has been successfully created.`,
    });
    setIsAddDialogOpen(false);
    resetForm();
  };

  const handleEditSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedCourse) return;
    
    const updatedCourses = courses.map((c) =>
      c.id === selectedCourse.id
        ? {
            ...c,
            ...formData,
            credits: parseInt(formData.credits),
          }
        : c
    );
    setCourses(updatedCourses);
    toast({
      title: "Course Updated",
      description: `${formData.name} has been successfully updated.`,
    });
    setIsEditDialogOpen(false);
    setSelectedCourse(null);
    resetForm();
  };

  const handleDelete = () => {
    if (!selectedCourse) return;
    
    setCourses(courses.filter((c) => c.id !== selectedCourse.id));
    toast({
      title: "Course Archived",
      description: `${selectedCourse.name} has been archived.`,
      variant: "destructive",
    });
    setIsDeleteDialogOpen(false);
    setSelectedCourse(null);
  };

  const handleEdit = (course: any) => {
    setSelectedCourse(course);
    setFormData({
      name: course.name,
      code: course.code,
      grade: course.grade,
      teacher: course.teacher,
      credits: course.credits.toString(),
      duration: course.duration,
      schedule: course.schedule,
      status: course.status,
    });
    setIsEditDialogOpen(true);
  };

  const handleDeleteClick = (course: any) => {
    setSelectedCourse(course);
    setIsDeleteDialogOpen(true);
  };

  const handleViewStudents = (course: any) => {
    setSelectedCourse(course);
    setIsStudentsDialogOpen(true);
  };

  const resetForm = () => {
    setFormData({
      name: "",
      code: "",
      grade: "",
      teacher: "",
      credits: "",
      duration: "",
      schedule: "",
      status: "Active",
    });
  };

  return (
    <DashboardLayout title="Courses" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <StatCard
          icon={<BookOpen className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Total Courses"
          value={totalCourses}
        />
        <StatCard
          icon={<GraduationCap className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Active Courses"
          value={activeCourses}
        />
        <StatCard
          icon={<Users className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Total Enrollments"
          value={totalEnrollments.toLocaleString()}
        />
      </div>

      {/* Main Content */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Course Management</CardTitle>
              <p className="text-sm text-muted-foreground mt-1">
                Manage curriculum, courses, and enrollments
              </p>
            </div>
            <div className="flex items-center gap-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  placeholder="Search courses..."
                  className="pl-10 w-64"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
              <Button onClick={() => setIsAddDialogOpen(true)}>
                <Plus className="w-4 h-4 mr-2" />
                Create Course
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Course</TableHead>
                  <TableHead>Code</TableHead>
                  <TableHead>Grade</TableHead>
                  <TableHead>Teacher</TableHead>
                  <TableHead>Students</TableHead>
                  <TableHead>Credits</TableHead>
                  <TableHead>Schedule</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredCourses.map((course) => (
                  <TableRow key={course.id}>
                    <TableCell>
                      <div>
                        <div className="font-medium">{course.name}</div>
                        <div className="text-sm text-muted-foreground flex items-center gap-2 mt-1">
                          <Clock className="w-3 h-3" />
                          {course.duration}
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline" className="font-mono">
                        {course.code}
                      </Badge>
                    </TableCell>
                    <TableCell>{course.grade}</TableCell>
                    <TableCell className="text-muted-foreground">{course.teacher}</TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Users className="w-4 h-4 text-muted-foreground" />
                        <span className="font-medium">{course.students}</span>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="secondary">{course.credits} Credits</Badge>
                    </TableCell>
                    <TableCell className="text-sm text-muted-foreground">
                      {course.schedule}
                    </TableCell>
                    <TableCell>
                      <Badge
                        variant={course.status === "Active" ? "default" : "secondary"}
                        className={course.status === "Active" ? "bg-green-500" : ""}
                      >
                        {course.status}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreVertical className="w-4 h-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem onClick={() => navigate(`/admin/courses/${course.id}`)}>
                            <Eye className="w-4 h-4 mr-2" />
                            View Details
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleEdit(course)}>
                            <Edit className="w-4 h-4 mr-2" />
                            Edit Course
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleViewStudents(course)}>
                            <Users className="w-4 h-4 mr-2" />
                            View Students
                          </DropdownMenuItem>
                          <DropdownMenuItem>
                            <Calendar className="w-4 h-4 mr-2" />
                            View Schedule
                          </DropdownMenuItem>
                          <DropdownMenuItem
                            className="text-destructive"
                            onClick={() => handleDeleteClick(course)}
                          >
                            <Trash2 className="w-4 h-4 mr-2" />
                            Archive
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Create Course Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Create New Course</DialogTitle>
            <DialogDescription>
              Enter the course information to create a new course.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleAddSubmit}>
            <div className="grid gap-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="name">Course Name *</Label>
                <Input
                  id="name"
                  required
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  placeholder="Mathematics - Algebra"
                />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="code">Course Code *</Label>
                  <Input
                    id="code"
                    required
                    value={formData.code}
                    onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                    placeholder="MATH-101"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="grade">Grade *</Label>
                  <Select
                    value={formData.grade}
                    onValueChange={(value) => setFormData({ ...formData, grade: value })}
                    required
                  >
                    <SelectTrigger id="grade">
                      <SelectValue placeholder="Select grade" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9">Grade 9</SelectItem>
                      <SelectItem value="Grade 10">Grade 10</SelectItem>
                      <SelectItem value="Grade 11">Grade 11</SelectItem>
                      <SelectItem value="Grade 12">Grade 12</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="teacher">Teacher *</Label>
                  <Select
                    value={formData.teacher}
                    onValueChange={(value) => setFormData({ ...formData, teacher: value })}
                    required
                  >
                    <SelectTrigger id="teacher">
                      <SelectValue placeholder="Select teacher" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Dr. Robert Anderson">Dr. Robert Anderson</SelectItem>
                      <SelectItem value="Prof. Maria Garcia">Prof. Maria Garcia</SelectItem>
                      <SelectItem value="Ms. Jennifer Lee">Ms. Jennifer Lee</SelectItem>
                      <SelectItem value="Mr. James Taylor">Mr. James Taylor</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="credits">Credits *</Label>
                  <Input
                    id="credits"
                    type="number"
                    required
                    value={formData.credits}
                    onChange={(e) => setFormData({ ...formData, credits: e.target.value })}
                    placeholder="3"
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="duration">Duration *</Label>
                  <Input
                    id="duration"
                    required
                    value={formData.duration}
                    onChange={(e) => setFormData({ ...formData, duration: e.target.value })}
                    placeholder="90 min"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="schedule">Schedule *</Label>
                  <Input
                    id="schedule"
                    required
                    value={formData.schedule}
                    onChange={(e) => setFormData({ ...formData, schedule: e.target.value })}
                    placeholder="Mon, Wed, Fri - 9:00 AM"
                  />
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Plus className="w-4 h-4 mr-2" />
                Create Course
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Edit Course Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Edit Course</DialogTitle>
            <DialogDescription>
              Update the course information in the system.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleEditSubmit}>
            <div className="grid gap-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="edit-name">Course Name *</Label>
                <Input
                  id="edit-name"
                  required
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  placeholder="Mathematics - Algebra"
                />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-code">Course Code *</Label>
                  <Input
                    id="edit-code"
                    required
                    value={formData.code}
                    onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                    placeholder="MATH-101"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-grade">Grade *</Label>
                  <Select
                    value={formData.grade}
                    onValueChange={(value) => setFormData({ ...formData, grade: value })}
                    required
                  >
                    <SelectTrigger id="edit-grade">
                      <SelectValue placeholder="Select grade" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9">Grade 9</SelectItem>
                      <SelectItem value="Grade 10">Grade 10</SelectItem>
                      <SelectItem value="Grade 11">Grade 11</SelectItem>
                      <SelectItem value="Grade 12">Grade 12</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-teacher">Teacher *</Label>
                  <Select
                    value={formData.teacher}
                    onValueChange={(value) => setFormData({ ...formData, teacher: value })}
                    required
                  >
                    <SelectTrigger id="edit-teacher">
                      <SelectValue placeholder="Select teacher" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Dr. Robert Anderson">Dr. Robert Anderson</SelectItem>
                      <SelectItem value="Prof. Maria Garcia">Prof. Maria Garcia</SelectItem>
                      <SelectItem value="Ms. Jennifer Lee">Ms. Jennifer Lee</SelectItem>
                      <SelectItem value="Mr. James Taylor">Mr. James Taylor</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-credits">Credits *</Label>
                  <Input
                    id="edit-credits"
                    type="number"
                    required
                    value={formData.credits}
                    onChange={(e) => setFormData({ ...formData, credits: e.target.value })}
                    placeholder="3"
                  />
                </div>
              </div>
              <div className="grid grid-cols-3 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-duration">Duration *</Label>
                  <Input
                    id="edit-duration"
                    required
                    value={formData.duration}
                    onChange={(e) => setFormData({ ...formData, duration: e.target.value })}
                    placeholder="90 min"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-schedule">Schedule *</Label>
                  <Input
                    id="edit-schedule"
                    required
                    value={formData.schedule}
                    onChange={(e) => setFormData({ ...formData, schedule: e.target.value })}
                    placeholder="Mon, Wed, Fri - 9:00 AM"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-status">Status *</Label>
                  <Select
                    value={formData.status}
                    onValueChange={(value) => setFormData({ ...formData, status: value })}
                    required
                  >
                    <SelectTrigger id="edit-status">
                      <SelectValue placeholder="Select status" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Active">Active</SelectItem>
                      <SelectItem value="Archived">Archived</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsEditDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Edit className="w-4 h-4 mr-2" />
                Update Course
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Delete/Archive Confirmation Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Archive Course?</AlertDialogTitle>
            <AlertDialogDescription>
              This will archive <span className="font-semibold">{selectedCourse?.name}</span>. 
              The course will be removed from active listings but can be restored later.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDelete}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              <Trash2 className="w-4 h-4 mr-2" />
              Archive
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* View Students Dialog */}
      <Dialog open={isStudentsDialogOpen} onOpenChange={setIsStudentsDialogOpen}>
        <DialogContent className="max-w-3xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <Users className="w-5 h-5" />
              Students - {selectedCourse?.name}
            </DialogTitle>
            <DialogDescription>
              View all students enrolled in this course.
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedCourse && (
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Student ID</TableHead>
                      <TableHead>Name</TableHead>
                      <TableHead>Grade</TableHead>
                      <TableHead>Attendance</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {getCourseStudents(selectedCourse.id).map((student: any) => (
                      <TableRow key={student.id}>
                        <TableCell className="font-mono text-sm">{student.id}</TableCell>
                        <TableCell className="font-medium">{student.name}</TableCell>
                        <TableCell>
                          <Badge variant="secondary" className="font-semibold">
                            {student.grade}
                          </Badge>
                        </TableCell>
                        <TableCell>
                          <Badge
                            variant={student.attendance >= 95 ? "default" : "secondary"}
                            className={student.attendance >= 95 ? "bg-green-500" : ""}
                          >
                            {student.attendance}%
                          </Badge>
                        </TableCell>
                      </TableRow>
                    ))}
                    {getCourseStudents(selectedCourse.id).length === 0 && (
                      <TableRow>
                        <TableCell colSpan={4} className="text-center text-muted-foreground py-8">
                          No students enrolled in this course yet.
                        </TableCell>
                      </TableRow>
                    )}
                  </TableBody>
                </Table>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsStudentsDialogOpen(false)}>
              Close
            </Button>
            <Button onClick={() => {
              setIsStudentsDialogOpen(false);
              if (selectedCourse) {
                navigate(`/admin/courses/${selectedCourse.id}`);
              }
            }}>
              View Full Details
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

